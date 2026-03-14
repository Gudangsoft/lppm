<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchRoadmapTranslation extends Model
{
    protected $fillable = ['research_roadmap_id', 'locale', 'title', 'description', 'content'];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(ResearchRoadmap::class, 'research_roadmap_id');
    }
}
