<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrant;
use App\Models\InternalGrantFinalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternalGrantFinalReportController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalGrantFinalReport::with(['grant.submission.researcher']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15);
        return view('admin.internal-grants.reports.final.index', compact('reports'));
    }

    public function create(InternalGrant $grant)
    {
        if ($grant->finalReport) {
            return redirect()->route('admin.internal-grants.final-reports.edit', $grant->finalReport);
        }
        
        $grant->load(['submission.researcher', 'outputs']);
        return view('admin.internal-grants.reports.final.create', compact('grant'));
    }

    public function store(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'executive_summary' => ['required', 'string'],
            'results' => ['required', 'string'],
            'conclusion' => ['required', 'string'],
            'total_budget_spent' => ['required', 'numeric', 'min:0'],
        ]);

        $report = $grant->finalReport()->create([
            'executive_summary' => $request->executive_summary,
            'introduction' => $request->introduction,
            'methodology' => $request->methodology,
            'results' => $request->results,
            'discussion' => $request->discussion,
            'conclusion' => $request->conclusion,
            'recommendations' => $request->recommendations,
            'references' => $request->references,
            'total_budget_spent' => $request->total_budget_spent,
            'budget_realization_detail' => $request->budget_realization_detail,
            'status' => $request->status ?? 'draft',
        ]);

        if ($request->hasFile('report_file')) {
            $report->update([
                'report_file' => $request->file('report_file')->store('internal-grants/final-reports', 'public')
            ]);
        }

        if ($request->hasFile('financial_report_file')) {
            $report->update([
                'financial_report_file' => $request->file('financial_report_file')->store('internal-grants/final-reports', 'public')
            ]);
        }

        return redirect()->route('admin.internal-grants.grants.show', $grant)
            ->with('success', 'Laporan akhir berhasil dibuat');
    }

    public function show(InternalGrantFinalReport $report)
    {
        $report->load(['grant.submission.researcher', 'grant.outputs', 'reviewer']);
        return view('admin.internal-grants.reports.final.show', compact('report'));
    }

    public function edit(InternalGrantFinalReport $report)
    {
        $report->load(['grant.submission.researcher', 'grant.outputs']);
        return view('admin.internal-grants.reports.final.edit', compact('report'));
    }

    public function update(Request $request, InternalGrantFinalReport $report)
    {
        $request->validate([
            'executive_summary' => ['required', 'string'],
            'results' => ['required', 'string'],
            'conclusion' => ['required', 'string'],
            'total_budget_spent' => ['required', 'numeric', 'min:0'],
        ]);

        $report->update($request->except(['report_file', 'financial_report_file']));

        if ($request->hasFile('report_file')) {
            if ($report->report_file) {
                Storage::disk('public')->delete($report->report_file);
            }
            $report->update([
                'report_file' => $request->file('report_file')->store('internal-grants/final-reports', 'public')
            ]);
        }

        if ($request->hasFile('financial_report_file')) {
            if ($report->financial_report_file) {
                Storage::disk('public')->delete($report->financial_report_file);
            }
            $report->update([
                'financial_report_file' => $request->file('financial_report_file')->store('internal-grants/final-reports', 'public')
            ]);
        }

        return redirect()->route('admin.internal-grants.grants.show', $report->grant)
            ->with('success', 'Laporan akhir berhasil diperbarui');
    }

    public function destroy(InternalGrantFinalReport $report)
    {
        $grant = $report->grant;

        if ($report->report_file) {
            Storage::disk('public')->delete($report->report_file);
        }
        if ($report->financial_report_file) {
            Storage::disk('public')->delete($report->financial_report_file);
        }
        
        $report->delete();

        return redirect()->route('admin.internal-grants.grants.show', $grant)
            ->with('success', 'Laporan akhir berhasil dihapus');
    }

    public function review(Request $request, InternalGrantFinalReport $report)
    {
        $request->validate([
            'status' => ['required', 'in:approved,revision'],
            'final_score' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $report->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'reviewer_notes' => $request->reviewer_notes,
            'final_score' => $request->final_score,
        ]);

        // If approved, mark grant as completed
        if ($request->status === 'approved') {
            $report->grant->update(['status' => 'completed']);
        }

        return back()->with('success', 'Review laporan akhir berhasil disimpan');
    }

    public function submit(InternalGrantFinalReport $report)
    {
        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Laporan akhir berhasil diajukan');
    }
}
