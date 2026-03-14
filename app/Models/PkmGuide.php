<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class PkmGuide extends Model
{
    use Translatable;

    protected $fillable = ['slug', 'file', 'year', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $translatable = ['title', 'description'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
