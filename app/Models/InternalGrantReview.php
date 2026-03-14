<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalGrantReview extends Model
{
    protected $fillable = [
        'submission_id', 'reviewer_id', 'score_relevance', 'score_methodology',
        'score_output', 'score_budget', 'score_team', 'total_score',
        'comments', 'suggestions', 'recommendation', 'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(InternalGrantSubmission::class, 'submission_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function calculateTotalScore(): int
    {
        $scores = [
            $this->score_relevance ?? 0,
            $this->score_methodology ?? 0,
            $this->score_output ?? 0,
            $this->score_budget ?? 0,
            $this->score_team ?? 0,
        ];
        return (int) round(array_sum($scores) / 5);
    }

    public function getRecommendationBadgeAttribute(): string
    {
        return match($this->recommendation) {
            'accept' => 'bg-green-100 text-green-800',
            'revision' => 'bg-yellow-100 text-yellow-800',
            'reject' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
