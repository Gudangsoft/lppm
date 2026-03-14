<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'category_id', 'file', 'file_type', 'file_size',
        'download_count', 'is_published', 'order', 'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'description'];

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
        $this->increment('download_count');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
