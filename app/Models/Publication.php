<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'type', 'journal_id', 'category_id', 'year',
        'volume', 'issue', 'pages', 'doi', 'url', 'file',
        'cover_image', 'is_published', 'created_by'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'abstract', 'keywords'];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function authors(): HasMany
    {
        return $this->hasMany(PublicationAuthor::class)->orderBy('order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
