<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class ResearchRoadmap extends Model
{
    use Translatable;

    protected $fillable = ['slug', 'year_start', 'year_end', 'image', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $translatable = ['title', 'description', 'content'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
