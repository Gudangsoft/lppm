<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalGrantFinalReport extends Model
{
    protected $fillable = [
        'grant_id', 'executive_summary', 'introduction', 'methodology',
        'results', 'discussion', 'conclusion', 'recommendations', 'references',
        'total_budget_spent', 'budget_realization_detail', 'report_file',
        'financial_report_file', 'output_evidence_files', 'status',
        'submitted_at', 'reviewed_by', 'reviewed_at', 'reviewer_notes', 'final_score'
    ];

    protected $casts = [
        'total_budget_spent' => 'decimal:2',
        'budget_realization_detail' => 'array',
        'output_evidence_files' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function grant(): BelongsTo
    {
        return $this->belongsTo(InternalGrant::class, 'grant_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'revision' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
            default => $this->status,
        };
    }

    public function getBudgetRealizationPercentageAttribute(): float
    {
        $approved = $this->grant?->approved_budget ?? 0;
        if ($approved == 0) return 0;
        return round(($this->total_budget_spent / $approved) * 100, 2);
    }
}
