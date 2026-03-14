<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationTranslation extends Model
{
    protected $fillable = ['publication_id', 'locale', 'title', 'abstract', 'keywords'];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }
}
