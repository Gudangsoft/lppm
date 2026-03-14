<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grant extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'type', 'funding_source', 'year', 'total_budget',
        'deadline', 'status', 'image', 'document_file', 'is_published', 'created_by'
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
        'deadline' => 'date',
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'description', 'requirements', 'benefits'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
