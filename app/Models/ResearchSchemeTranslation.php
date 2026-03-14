<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchSchemeTranslation extends Model
{
    protected $fillable = ['research_scheme_id', 'locale', 'name', 'description', 'requirements'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(ResearchScheme::class, 'research_scheme_id');
    }
}
