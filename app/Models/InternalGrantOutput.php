<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalGrantOutput extends Model
{
    protected $fillable = [
        'grant_id', 'output_type', 'title', 'description', 'status',
        'evidence_file', 'url', 'completion_date'
    ];

    protected $casts = [
        'completion_date' => 'date',
    ];

    public function grant(): BelongsTo
    {
        return $this->belongsTo(InternalGrant::class, 'grant_id');
    }

    public function getOutputTypeLabelAttribute(): string
    {
        return match($this->output_type) {
            'publication' => 'Publikasi Jurnal',
            'proceeding' => 'Prosiding',
            'hki' => 'HKI/Paten',
            'prototype' => 'Prototipe/Produk',
            'book' => 'Buku/Monograf',
            'model' => 'Model/TTG',
            'software' => 'Perangkat Lunak',
            'other' => 'Luaran Lainnya',
            default => $this->output_type,
        };
    }

    // Alias for views that use type_label
    public function getTypeLabelAttribute(): string
    {
        return $this->output_type_label;
    }

    // Alias for views that use type instead of output_type
    public function getTypeAttribute(): string
    {
        return $this->output_type ?? '';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'planned' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
