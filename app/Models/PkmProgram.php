<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PkmProgram extends Model
{
    use Translatable;

    protected $fillable = ['slug', 'code', 'is_active', 'order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $translatable = ['name', 'description'];

    public function pengabdians(): HasMany
    {
        return $this->hasMany(Pengabdian::class, 'program_id');
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
