<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hki extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'type', 'registration_number', 'certificate_number',
        'filing_date', 'registration_date', 'expiry_date', 'status',
        'certificate_file', 'image', 'is_published', 'created_by'
    ];

    protected $casts = [
        'filing_date' => 'date',
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'description', 'inventors'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
