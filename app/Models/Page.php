<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'template', 'featured_image', 'is_published', 'order', 'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
