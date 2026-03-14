<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengabdian extends Model
{
    use Translatable;

    protected $fillable = [
        'slug', 'program_id', 'category_id', 'year', 'budget',
        'location', 'partner', 'status', 'featured_image', 'is_published', 'created_by'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    protected $translatable = ['title', 'abstract', 'content', 'output'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(PkmProgram::class, 'program_id');
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
        return $this->belongsToMany(Researcher::class, 'pengabdian_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function leader()
    {
        return $this->team()->wherePivot('role', 'leader')->first();
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
