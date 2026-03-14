<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HkiTranslation extends Model
{
    protected $fillable = ['hki_id', 'locale', 'title', 'description', 'inventors'];

    public function hki(): BelongsTo
    {
        return $this->belongsTo(Hki::class);
    }
}
