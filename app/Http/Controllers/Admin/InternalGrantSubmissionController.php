<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrantSubmission;
use App\Models\InternalGrantPeriod;
use App\Models\InternalGrantReview;
use App\Models\InternalGrant;
use App\Models\Researcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternalGrantSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = InternalGrantSubmission::with(['period.scheme', 'researcher', 'reviews']);

        if ($request->filled('period')) {
            $query->where('period_id', $request->period);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%");
            });
        }

        $submissions = $query->latest()->paginate(15);
        $periods = InternalGrantPeriod::with('scheme')->latest()->get();

        return view('admin.internal-grants.submissions.index', compact('submissions', 'periods'));
    }

    public function create()
    {
        $periods = InternalGrantPeriod::with('scheme')->where('status', 'open')->get();
        $researchers = Researcher::active()->get();
        return view('admin.internal-grants.submissions.create', compact('periods', 'researchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_id' => ['required', 'exists:internal_grant_periods,id'],
            'researcher_id' => ['required', 'exists:researchers,id'],
            'title' => ['required', 'string', 'max:500'],
            'requested_budget' => ['nullable', 'numeric', 'min:0'],
        ]);

        $submission = InternalGrantSubmission::create([
            'registration_number' => InternalGrantSubmission::generateRegistrationNumber($request->period_id),
            'period_id' => $request->period_id,
            'researcher_id' => $request->researcher_id,
            'title' => $request->title,
            'abstract' => $request->abstract,
            'background' => $request->background,
            'objectives' => $request->objectives,
            'methodology' => $request->methodology,
            'expected_output' => $request->expected_output,
            'timeline' => $request->timeline,
            'requested_budget' => $request->requested_budget,
            'budget_details' => $request->budget_details,
            'status' => $request->status ?? 'draft',
        ]);

        if ($request->hasFile('proposal_file')) {
            $submission->update([
                'proposal_file' => $request->file('proposal_file')->store('internal-grants/proposals', 'public')
            ]);
        }

        // Add team members
        if ($request->has('team')) {
            foreach ($request->team as $member) {
                $submission->team()->attach($member['researcher_id'], ['role' => $member['role']]);
            }
        }

        return redirect()->route('admin.internal-grants.submissions.index')
            ->with('success', 'Pengajuan berhasil dibuat');
    }

    public function show(InternalGrantSubmission $submission)
    {
        $submission->load(['period.scheme', 'researcher', 'team', 'reviews.reviewer', 'grant']);
        return view('admin.internal-grants.submissions.show', compact('submission'));
    }

    public function edit(InternalGrantSubmission $submission)
    {
        $periods = InternalGrantPeriod::with('scheme')->get();
        $researchers = Researcher::active()->get();
        $submission->load('team');
        return view('admin.internal-grants.submissions.edit', compact('submission', 'periods', 'researchers'));
    }

    public function update(Request $request, InternalGrantSubmission $submission)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'requested_budget' => ['nullable', 'numeric', 'min:0'],
        ]);

        $submission->update($request->except(['proposal_file', 'team']));

        if ($request->hasFile('proposal_file')) {
            if ($submission->proposal_file) {
                Storage::disk('public')->delete($submission->proposal_file);
            }
            $submission->update([
                'proposal_file' => $request->file('proposal_file')->store('internal-grants/proposals', 'public')
            ]);
        }

        // Update team members
        if ($request->has('team')) {
            $submission->team()->detach();
            foreach ($request->team as $member) {
                $submission->team()->attach($member['researcher_id'], ['role' => $member['role']]);
            }
        }

        return redirect()->route('admin.internal-grants.submissions.show', $submission)
            ->with('success', 'Pengajuan berhasil diperbarui');
    }

    public function destroy(InternalGrantSubmission $submission)
    {
        if ($submission->proposal_file) {
            Storage::disk('public')->delete($submission->proposal_file);
        }

        $submission->delete();

        return redirect()->route('admin.internal-grants.submissions.index')
            ->with('success', 'Pengajuan berhasil dihapus');
    }

    public function updateStatus(Request $request, InternalGrantSubmission $submission)
    {
        $request->validate([
            'status' => ['required', 'in:draft,submitted,under_review,revision,accepted,rejected,cancelled'],
        ]);

        $submission->update([
            'status' => $request->status,
            'submitted_at' => $request->status === 'submitted' ? now() : $submission->submitted_at,
        ]);

        // If accepted, create grant record
        if ($request->status === 'accepted' && !$submission->grant) {
            InternalGrant::create([
                'contract_number' => InternalGrant::generateContractNumber(),
                'submission_id' => $submission->id,
                'start_date' => now(),
                'end_date' => now()->addMonths($submission->period->scheme->max_duration_months ?? 12),
                'approved_budget' => $submission->requested_budget,
                'status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui');
    }

    public function review(InternalGrantSubmission $submission)
    {
        $submission->load(['period.scheme', 'researcher', 'team', 'reviews.reviewer']);
        return view('admin.internal-grants.submissions.review', compact('submission'));
    }

    public function storeReview(Request $request, InternalGrantSubmission $submission)
    {
        $request->validate([
            'score_relevance' => ['required', 'integer', 'min:0', 'max:100'],
            'score_methodology' => ['required', 'integer', 'min:0', 'max:100'],
            'score_output' => ['required', 'integer', 'min:0', 'max:100'],
            'score_budget' => ['required', 'integer', 'min:0', 'max:100'],
            'score_team' => ['required', 'integer', 'min:0', 'max:100'],
            'recommendation' => ['required', 'in:accept,revision,reject'],
        ]);

        $review = InternalGrantReview::updateOrCreate(
            [
                'submission_id' => $submission->id,
                'reviewer_id' => auth()->id(),
            ],
            [
                'score_relevance' => $request->score_relevance,
                'score_methodology' => $request->score_methodology,
                'score_output' => $request->score_output,
                'score_budget' => $request->score_budget,
                'score_team' => $request->score_team,
                'total_score' => round(($request->score_relevance + $request->score_methodology + $request->score_output + $request->score_budget + $request->score_team) / 5),
                'comments' => $request->comments,
                'suggestions' => $request->suggestions,
                'recommendation' => $request->recommendation,
                'reviewed_at' => now(),
            ]
        );

        return redirect()->route('admin.internal-grants.submissions.show', $submission)
            ->with('success', 'Review berhasil disimpan');
    }
}
