<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchTranslation extends Model
{
    protected $fillable = ['research_id', 'locale', 'title', 'abstract', 'content', 'keywords'];

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }
}
