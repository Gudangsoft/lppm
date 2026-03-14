<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InternalGrantSubmission extends Model
{
    protected $fillable = [
        'registration_number', 'period_id', 'researcher_id', 'title', 'abstract',
        'background', 'objectives', 'methodology', 'expected_output', 'timeline',
        'requested_budget', 'budget_details', 'proposal_file', 'supporting_documents',
        'status', 'submitted_at'
    ];

    protected $casts = [
        'requested_budget' => 'decimal:2',
        'budget_details' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(InternalGrantPeriod::class, 'period_id');
    }

    public function researcher(): BelongsTo
    {
        return $this->belongsTo(Researcher::class);
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Researcher::class, 'internal_grant_submission_team', 'submission_id', 'researcher_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(InternalGrantReview::class, 'submission_id');
    }

    public function grant(): HasOne
    {
        return $this->hasOne(InternalGrant::class, 'submission_id');
    }

    public function scopeSubmitted($query)
    {
        return $query->whereNotIn('status', ['draft', 'cancelled']);
    }

    public function getAverageScoreAttribute(): ?float
    {
        $reviews = $this->reviews()->whereNotNull('total_score')->get();
        if ($reviews->isEmpty()) return null;
        return round($reviews->avg('total_score'), 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'revision' => 'bg-orange-100 text-orange-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'under_review' => 'Sedang Direview',
            'revision' => 'Revisi',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public static function generateRegistrationNumber($periodId): string
    {
        $period = InternalGrantPeriod::with('scheme')->find($periodId);
        $count = self::where('period_id', $periodId)->count() + 1;
        $code = $period->scheme->code ?? 'HI';
        return sprintf('%s/%s/%04d', $code, $period->year, $count);
    }
}
