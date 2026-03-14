<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrantTranslation extends Model
{
    protected $fillable = ['grant_id', 'locale', 'title', 'description', 'requirements', 'benefits'];

    public function grant(): BelongsTo
    {
        return $this->belongsTo(Grant::class);
    }
}
