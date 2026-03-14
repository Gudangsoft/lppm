<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PkmGuideTranslation extends Model
{
    protected $fillable = ['pkm_guide_id', 'locale', 'title', 'description'];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(PkmGuide::class, 'pkm_guide_id');
    }
}
