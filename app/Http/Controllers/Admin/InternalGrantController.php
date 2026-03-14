<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrant;
use App\Models\InternalGrantDisbursement;
use App\Models\InternalGrantOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternalGrantController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalGrant::with(['submission.researcher', 'submission.period.scheme']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('contract_number', 'like', "%{$request->search}%")
                  ->orWhereHas('submission', function($sq) use ($request) {
                      $sq->where('title', 'like', "%{$request->search}%");
                  });
            });
        }

        $grants = $query->latest()->paginate(15);
        $years = InternalGrant::selectRaw('YEAR(start_date) as year')->distinct()->pluck('year')->filter()->sort()->reverse();

        return view('admin.internal-grants.grants.index', compact('grants', 'years'));
    }

    public function show(InternalGrant $grant)
    {
        $grant->load([
            'submission.researcher', 
            'submission.period.scheme', 
            'submission.team',
            'disbursements',
            'progressReports',
            'outputs',
            'finalReport'
        ]);

        return view('admin.internal-grants.grants.show', compact('grant'));
    }

    public function edit(InternalGrant $grant)
    {
        $grant->load('submission');
        return view('admin.internal-grants.grants.edit', compact('grant'));
    }

    public function update(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'contract_number' => ['required', 'string', 'unique:internal_grants,contract_number,' . $grant->id],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'approved_budget' => ['required', 'numeric', 'min:0'],
        ]);

        $grant->update($request->except('contract_file'));

        if ($request->hasFile('contract_file')) {
            if ($grant->contract_file) {
                Storage::disk('public')->delete($grant->contract_file);
            }
            $grant->update([
                'contract_file' => $request->file('contract_file')->store('internal-grants/contracts', 'public')
            ]);
        }

        return redirect()->route('admin.internal-grants.grants.show', $grant)
            ->with('success', 'Data hibah berhasil diperbarui');
    }

    public function updateStatus(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'status' => ['required', 'in:active,completed,terminated,extended'],
        ]);

        $grant->update(['status' => $request->status]);

        return back()->with('success', 'Status hibah berhasil diperbarui');
    }

    // Disbursements
    public function storeDisbursement(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'phase' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'disbursement_date' => ['nullable', 'date'],
        ]);

        $grant->disbursements()->create([
            'phase' => $request->phase,
            'amount' => $request->amount,
            'disbursement_date' => $request->disbursement_date,
            'status' => $request->status ?? 'pending',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Pencairan dana berhasil ditambahkan');
    }

    public function updateDisbursement(Request $request, InternalGrantDisbursement $disbursement)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processed,completed'],
        ]);

        $disbursement->update([
            'status' => $request->status,
            'disbursement_date' => $request->status === 'completed' ? now() : $disbursement->disbursement_date,
            'processed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Status pencairan berhasil diperbarui');
    }

    // Outputs
    public function storeOutput(Request $request, InternalGrant $grant)
    {
        $request->validate([
            'output_type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:500'],
        ]);

        $output = $grant->outputs()->create($request->except('evidence_file'));

        if ($request->hasFile('evidence_file')) {
            $output->update([
                'evidence_file' => $request->file('evidence_file')->store('internal-grants/outputs', 'public')
            ]);
        }

        return back()->with('success', 'Luaran berhasil ditambahkan');
    }

    public function updateOutput(Request $request, InternalGrantOutput $output)
    {
        $output->update($request->except('evidence_file'));

        if ($request->hasFile('evidence_file')) {
            if ($output->evidence_file) {
                Storage::disk('public')->delete($output->evidence_file);
            }
            $output->update([
                'evidence_file' => $request->file('evidence_file')->store('internal-grants/outputs', 'public')
            ]);
        }

        return back()->with('success', 'Luaran berhasil diperbarui');
    }

    public function destroyOutput(InternalGrantOutput $output)
    {
        if ($output->evidence_file) {
            Storage::disk('public')->delete($output->evidence_file);
        }
        $output->delete();

        return back()->with('success', 'Luaran berhasil dihapus');
    }
}
