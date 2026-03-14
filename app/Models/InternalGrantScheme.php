<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalGrantScheme extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'max_budget', 
        'max_duration_months', 'requirements', 'output_requirements', 'is_active'
    ];

    protected $casts = [
        'max_budget' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(InternalGrantPeriod::class, 'scheme_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
