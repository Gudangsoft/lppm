<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrant;
use App\Models\InternalGrantProgressReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternalGrantProgressReportController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalGrantProgressReport::with(['grant.submission.researcher']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('report_type', $request->type);
        }

        $reports = $query->latest()->paginate(15);
        return view('admin.internal-grants.reports.progress.index', compact('reports'));
    }

    public function create(InternalGrant $grant)
    {
        $grant->load('submission.researcher');
        return view('admin.internal-grants.reports.progress.create', compact('grant'));
    }

    public function store(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'period' => ['required', 'string'],
            'activities' => ['required', 'string'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'budget_spent' => ['nullable', 'numeric', 'min:0'],
        ]);

        $report = $grant->progressReports()->create([
            'report_type' => $request->report_type ?? 'progress',
            'period' => $request->period,
            'activities' => $request->activities,
            'achievements' => $request->achievements,
            'obstacles' => $request->obstacles,
            'solutions' => $request->solutions,
            'progress_percentage' => $request->progress_percentage,
            'budget_realization' => $request->budget_realization,
            'budget_spent' => $request->budget_spent ?? 0,
            'status' => $request->status ?? 'draft',
        ]);

        if ($request->hasFile('report_file')) {
            $report->update([
                'report_file' => $request->file('report_file')->store('internal-grants/progress-reports', 'public')
            ]);
        }

        return redirect()->route('admin.internal-grants.grants.show', $grant)
            ->with('success', 'Laporan kemajuan berhasil dibuat');
    }

    public function show(InternalGrantProgressReport $report)
    {
        $report->load(['grant.submission.researcher', 'reviewer']);
        return view('admin.internal-grants.reports.progress.show', compact('report'));
    }

    public function edit(InternalGrantProgressReport $report)
    {
        $report->load('grant.submission.researcher');
        return view('admin.internal-grants.reports.progress.edit', compact('report'));
    }

    public function update(Request $request, InternalGrantProgressReport $report)
    {
        $request->validate([
            'period' => ['required', 'string'],
            'activities' => ['required', 'string'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $report->update($request->except('report_file'));

        if ($request->hasFile('report_file')) {
            if ($report->report_file) {
                Storage::disk('public')->delete($report->report_file);
            }
            $report->update([
                'report_file' => $request->file('report_file')->store('internal-grants/progress-reports', 'public')
            ]);
        }

        return redirect()->route('admin.internal-grants.grants.show', $report->grant)
            ->with('success', 'Laporan kemajuan berhasil diperbarui');
    }

    public function destroy(InternalGrantProgressReport $report)
    {
        $grant = $report->grant;

        if ($report->report_file) {
            Storage::disk('public')->delete($report->report_file);
        }
        $report->delete();

        return redirect()->route('admin.internal-grants.grants.show', $grant)
            ->with('success', 'Laporan kemajuan berhasil dihapus');
    }

    public function review(Request $request, InternalGrantProgressReport $report)
    {
        $request->validate([
            'status' => ['required', 'in:approved,revision'],
        ]);

        $report->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'reviewer_notes' => $request->reviewer_notes,
        ]);

        return back()->with('success', 'Review laporan berhasil disimpan');
    }

    public function submit(InternalGrantProgressReport $report)
    {
        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Laporan berhasil diajukan');
    }
}
