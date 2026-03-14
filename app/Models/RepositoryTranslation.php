<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepositoryTranslation extends Model
{
    protected $fillable = ['repository_id', 'locale', 'title', 'abstract', 'keywords', 'authors'];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
