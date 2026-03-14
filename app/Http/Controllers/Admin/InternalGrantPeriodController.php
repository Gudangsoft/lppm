<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrantPeriod;
use App\Models\InternalGrantScheme;
use Illuminate\Http\Request;

class InternalGrantPeriodController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalGrantPeriod::with(['scheme', 'submissions']);

        if ($request->filled('scheme')) {
            $query->where('scheme_id', $request->scheme);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $periods = $query->latest()->paginate(15);
        $schemes = InternalGrantScheme::active()->get();
        $years = InternalGrantPeriod::distinct()->pluck('year')->sort()->reverse();

        return view('admin.internal-grants.periods.index', compact('periods', 'schemes', 'years'));
    }

    public function create()
    {
        $schemes = InternalGrantScheme::active()->get();
        return view('admin.internal-grants.periods.create', compact('schemes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'scheme_id' => ['required', 'exists:internal_grant_schemes,id'],
            'year' => ['required', 'string', 'max:4'],
            'submission_start' => ['required', 'date'],
            'submission_end' => ['required', 'date', 'after:submission_start'],
            'total_budget_available' => ['nullable', 'numeric', 'min:0'],
        ]);

        InternalGrantPeriod::create($request->all());

        return redirect()->route('admin.internal-grants.periods.index')
            ->with('success', 'Periode pengajuan berhasil dibuat');
    }

    public function show(InternalGrantPeriod $period)
    {
        $period->load(['scheme', 'submissions.researcher', 'submissions.reviews']);
        return view('admin.internal-grants.periods.show', compact('period'));
    }

    public function edit(InternalGrantPeriod $period)
    {
        $schemes = InternalGrantScheme::active()->get();
        return view('admin.internal-grants.periods.edit', compact('period', 'schemes'));
    }

    public function update(Request $request, InternalGrantPeriod $period)
    {
        $request->validate([
            'scheme_id' => ['required', 'exists:internal_grant_schemes,id'],
            'year' => ['required', 'string', 'max:4'],
            'submission_start' => ['required', 'date'],
            'submission_end' => ['required', 'date', 'after:submission_start'],
            'total_budget_available' => ['nullable', 'numeric', 'min:0'],
        ]);

        $period->update($request->all());

        return redirect()->route('admin.internal-grants.periods.index')
            ->with('success', 'Periode pengajuan berhasil diperbarui');
    }

    public function destroy(InternalGrantPeriod $period)
    {
        $period->delete();

        return redirect()->route('admin.internal-grants.periods.index')
            ->with('success', 'Periode pengajuan berhasil dihapus');
    }

    public function updateStatus(Request $request, InternalGrantPeriod $period)
    {
        $request->validate([
            'status' => ['required', 'in:draft,open,closed,review,announced'],
        ]);

        $period->update(['status' => $request->status]);

        return back()->with('success', 'Status periode berhasil diperbarui');
    }
}
