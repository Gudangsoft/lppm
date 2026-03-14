<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalGrantDisbursement extends Model
{
    protected $fillable = [
        'grant_id', 'phase', 'amount', 'disbursement_date', 'status',
        'proof_file', 'notes', 'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'disbursement_date' => 'date',
    ];

    public function grant(): BelongsTo
    {
        return $this->belongsTo(InternalGrant::class, 'grant_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processed' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
