<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalGrant;
use App\Models\InternalGrantSubmission;
use App\Models\InternalGrantPeriod;
use App\Models\InternalGrantScheme;
use App\Models\InternalGrantOutput;
use App\Models\Researcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalGrantReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_submissions' => InternalGrantSubmission::count(),
            'accepted_submissions' => InternalGrantSubmission::where('status', 'accepted')->count(),
            'active_grants' => InternalGrant::where('status', 'active')->count(),
            'total_outputs' => InternalGrantOutput::count(),
        ];

        return view('admin.internal-grants.reports.index', compact('stats'));
    }

    public function submissions(Request $request)
    {
        $query = InternalGrantSubmission::with(['period.scheme', 'researcher']);

        if ($request->filled('year')) {
            $query->whereHas('period', fn($q) => $q->where('year', $request->year));
        }

        if ($request->filled('scheme')) {
            $query->whereHas('period', fn($q) => $q->where('scheme_id', $request->scheme));
        }

        $submissions = $query->latest()->paginate(20);
        
        // Summary stats
        $allSubmissions = InternalGrantSubmission::query();
        if ($request->filled('year')) {
            $allSubmissions->whereHas('period', fn($q) => $q->where('year', $request->year));
        }
        if ($request->filled('scheme')) {
            $allSubmissions->whereHas('period', fn($q) => $q->where('scheme_id', $request->scheme));
        }

        $total = $allSubmissions->count();
        $accepted = (clone $allSubmissions)->where('status', 'accepted')->count();
        $rejected = (clone $allSubmissions)->where('status', 'rejected')->count();
        $underReview = (clone $allSubmissions)->where('status', 'under_review')->count();

        $summary = [
            'total' => $total,
            'accepted' => $accepted,
            'rejected' => $rejected,
            'under_review' => $underReview,
            'acceptance_rate' => $total > 0 ? round(($accepted / $total) * 100, 1) : 0,
        ];

        // By Scheme
        $byScheme = InternalGrantSubmission::select(
            'internal_grant_periods.scheme_id',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(CASE WHEN internal_grant_submissions.status = "accepted" THEN 1 ELSE 0 END) as accepted')
        )
        ->join('internal_grant_periods', 'internal_grant_submissions.period_id', '=', 'internal_grant_periods.id')
        ->join('internal_grant_schemes', 'internal_grant_periods.scheme_id', '=', 'internal_grant_schemes.id')
        ->addSelect('internal_grant_schemes.name as scheme_name')
        ->groupBy('internal_grant_periods.scheme_id', 'internal_grant_schemes.name')
        ->get();

        // By Status
        $byStatus = InternalGrantSubmission::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $years = InternalGrantPeriod::distinct()->pluck('year')->filter()->sort()->reverse()->values();
        $schemes = InternalGrantScheme::where('is_active', true)->get();

        return view('admin.internal-grants.reports.submissions', compact(
            'submissions', 'summary', 'byScheme', 'byStatus', 'years', 'schemes'
        ));
    }

    public function activeGrants(Request $request)
    {
        $query = InternalGrant::with([
            'submission.researcher', 
            'submission.period.scheme',
            'progressReports',
            'finalReport'
        ]);

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $grants = $query->latest()->paginate(20);

        // Add latest_progress attribute
        $grants->getCollection()->transform(function ($grant) {
            $grant->latest_progress = $grant->progressReports->max('progress_percentage') ?? 0;
            return $grant;
        });

        $summary = [
            'active' => InternalGrant::where('status', 'active')->count(),
            'completed' => InternalGrant::where('status', 'completed')->count(),
            'needs_report' => InternalGrant::where('status', 'active')
                ->whereDoesntHave('progressReports', function($q) {
                    $q->where('created_at', '>=', now()->subMonths(3));
                })->count(),
            'total_budget' => InternalGrant::sum('approved_budget'),
        ];

        $years = InternalGrant::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->sort()
            ->reverse()
            ->values();

        return view('admin.internal-grants.reports.active-grants', compact('grants', 'summary', 'years'));
    }

    public function budgetRealization(Request $request)
    {
        $query = InternalGrant::with(['submission.period.scheme', 'finalReport', 'disbursements'])
            ->withSum('disbursements', 'amount');

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        $grants = $query->paginate(20);

        // By Scheme stats
        $byScheme = InternalGrant::select(
            'internal_grant_schemes.name as scheme_name',
            DB::raw('COUNT(*) as grant_count'),
            DB::raw('SUM(internal_grants.approved_budget) as approved'),
            DB::raw('COALESCE(SUM(disbursement_totals.total_disbursed), 0) as disbursed')
        )
        ->join('internal_grant_submissions', 'internal_grants.submission_id', '=', 'internal_grant_submissions.id')
        ->join('internal_grant_periods', 'internal_grant_submissions.period_id', '=', 'internal_grant_periods.id')
        ->join('internal_grant_schemes', 'internal_grant_periods.scheme_id', '=', 'internal_grant_schemes.id')
        ->leftJoin(DB::raw('(SELECT grant_id, SUM(amount) as total_disbursed FROM internal_grant_disbursements GROUP BY grant_id) as disbursement_totals'), 'internal_grants.id', '=', 'disbursement_totals.grant_id')
        ->groupBy('internal_grant_schemes.id', 'internal_grant_schemes.name')
        ->get()
        ->map(function ($item) {
            $item->realized = $item->disbursed;
            $item->percentage = $item->approved > 0 ? round(($item->disbursed / $item->approved) * 100, 1) : 0;
            return $item;
        });

        $summary = [
            'total_approved' => InternalGrant::sum('approved_budget'),
            'total_disbursed' => DB::table('internal_grant_disbursements')->sum('amount'),
            'total_realized' => DB::table('internal_grant_disbursements')->sum('amount'),
            'realization_rate' => 0,
        ];
        
        if ($summary['total_approved'] > 0) {
            $summary['realization_rate'] = round(($summary['total_disbursed'] / $summary['total_approved']) * 100, 1);
        }

        $years = InternalGrant::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->sort()
            ->reverse()
            ->values();

        return view('admin.internal-grants.reports.budget-realization', compact('grants', 'byScheme', 'summary', 'years'));
    }

    public function outputs(Request $request)
    {
        $query = InternalGrantOutput::with(['grant.submission.researcher', 'grant.submission.period.scheme']);

        if ($request->filled('year')) {
            $query->whereHas('grant', fn($q) => $q->whereYear('start_date', $request->year));
        }

        if ($request->filled('type')) {
            $query->where('output_type', $request->type);
        }

        $outputs = $query->latest()->paginate(20);

        // Stats by type
        $byType = [
            'publication' => InternalGrantOutput::where('output_type', 'publication')->count(),
            'proceeding' => InternalGrantOutput::where('output_type', 'proceeding')->count(),
            'book' => InternalGrantOutput::where('output_type', 'book')->count(),
            'hki' => InternalGrantOutput::where('output_type', 'hki')->count(),
            'prototype' => InternalGrantOutput::where('output_type', 'prototype')->count(),
            'other' => InternalGrantOutput::where('output_type', 'other')->count(),
        ];

        // Stats by status
        $byStatus = [
            'achieved' => InternalGrantOutput::where('status', 'achieved')->count(),
            'in_progress' => InternalGrantOutput::where('status', 'in_progress')->count(),
            'planned' => InternalGrantOutput::where('status', 'planned')->count(),
        ];

        $years = InternalGrant::selectRaw('YEAR(start_date) as year')
            ->distinct()
            ->pluck('year')
            ->filter()
            ->sort()
            ->reverse()
            ->values();

        return view('admin.internal-grants.reports.outputs', compact('outputs', 'byType', 'byStatus', 'years'));
    }

    public function researcherPerformance(Request $request)
    {
        $query = Researcher::withCount([
            'internalGrantSubmissions as submissions_count',
            'internalGrantSubmissions as accepted_count' => function ($q) {
                $q->where('status', 'accepted');
            }
        ]);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('nidn', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $researchers = $query->having('submissions_count', '>', 0)
            ->orderByDesc('accepted_count')
            ->paginate(20);

        // Add computed attributes
        $researchers->getCollection()->transform(function ($researcher) {
            $acceptedSubmissions = InternalGrantSubmission::where('researcher_id', $researcher->id)
                ->where('status', 'accepted')
                ->pluck('id');
            
            $grants = InternalGrant::whereIn('submission_id', $acceptedSubmissions)->get();
            
            $researcher->active_grants_count = $grants->where('status', 'active')->count();
            $researcher->completed_grants_count = $grants->where('status', 'completed')->count();
            $researcher->outputs_count = InternalGrantOutput::whereIn('grant_id', $grants->pluck('id'))->count();
            $researcher->total_budget = $grants->sum('approved_budget');
            
            return $researcher;
        });

        $summary = [
            'total_researchers' => Researcher::count(),
            'with_grants' => InternalGrantSubmission::distinct('researcher_id')->where('status', 'accepted')->count('researcher_id'),
            'avg_grants' => 0,
            'avg_outputs' => 0,
        ];

        $totalGrants = InternalGrant::count();
        $totalOutputs = InternalGrantOutput::count();
        
        if ($summary['with_grants'] > 0) {
            $summary['avg_grants'] = round($totalGrants / $summary['with_grants'], 1);
            $summary['avg_outputs'] = $totalGrants > 0 ? round($totalOutputs / $totalGrants, 1) : 0;
        }

        $departments = Researcher::distinct()->whereNotNull('department')->pluck('department');

        return view('admin.internal-grants.reports.researcher-performance', compact('researchers', 'summary', 'departments'));
    }

    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ekspor sedang dalam pengembangan.');
    }
}
