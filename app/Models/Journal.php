<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'issn', 'eissn', 'publisher', 'website', 
        'cover_image', 'accreditation', 'is_active', 'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $translatable = ['name', 'description', 'focus_scope'];

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
