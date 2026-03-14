<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchGuideTranslation extends Model
{
    protected $fillable = ['research_guide_id', 'locale', 'title', 'description'];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(ResearchGuide::class, 'research_guide_id');
    }
}
