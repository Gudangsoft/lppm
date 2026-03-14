<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalGrantProgressReport extends Model
{
    protected $fillable = [
        'grant_id', 'report_type', 'period', 'activities', 'achievements',
        'obstacles', 'solutions', 'progress_percentage', 'budget_realization',
        'budget_spent', 'report_file', 'attachments', 'status',
        'submitted_at', 'reviewed_by', 'reviewed_at', 'reviewer_notes'
    ];

    protected $casts = [
        'budget_realization' => 'array',
        'attachments' => 'array',
        'budget_spent' => 'decimal:2',
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
}
