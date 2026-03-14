<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Research extends Model
{
    use Translatable;

    protected $table = 'researches';

    protected $fillable = [
        'slug', 'scheme_id', 'category_id', 'year', 'budget', 
        'status', 'featured_image', 'is_published', 'created_by'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'abstract', 'content', 'keywords'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(ResearchScheme::class, 'scheme_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function team(): BelongsToMany
    {
        return $this->belongsToMany(Researcher::class, 'research_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function leader()
    {
        return $this->team()->wherePivot('role', 'leader')->first();
    }

    public function members()
    {
        return $this->team()->wherePivot('role', 'member');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
