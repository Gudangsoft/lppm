<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code', 'name', 'native_name', 'flag', 'is_default', 'is_active', 'order'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getDefault()
    {
        return static::where('is_default', true)->first() ?? static::first();
    }

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('order')->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
