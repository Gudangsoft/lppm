<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repository extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'type', 'category_id', 'year', 'file',
        'cover_image', 'downloads', 'is_published', 'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'abstract', 'keywords', 'authors'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
