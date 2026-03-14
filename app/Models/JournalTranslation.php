<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalTranslation extends Model
{
    protected $fillable = ['journal_id', 'locale', 'name', 'description', 'focus_scope'];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }
}
