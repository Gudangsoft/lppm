<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalGrantPeriod extends Model
{
    protected $fillable = [
        'scheme_id', 'year', 'semester', 'submission_start', 'submission_end',
        'review_start', 'review_end', 'announcement_date', 
        'total_budget_available', 'max_proposals', 'status'
    ];

    protected $casts = [
        'submission_start' => 'date',
        'submission_end' => 'date',
        'review_start' => 'date',
        'review_end' => 'date',
        'announcement_date' => 'date',
        'total_budget_available' => 'decimal:2',
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(InternalGrantScheme::class, 'scheme_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(InternalGrantSubmission::class, 'period_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where('submission_start', '<=', now())
            ->where('submission_end', '>=', now());
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' 
            && $this->submission_start <= now() 
            && $this->submission_end >= now();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'open' => 'bg-green-100 text-green-800',
            'closed' => 'bg-red-100 text-red-800',
            'review' => 'bg-yellow-100 text-yellow-800',
            'announced' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
