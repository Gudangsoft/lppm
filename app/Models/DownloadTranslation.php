<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownloadTranslation extends Model
{
    protected $fillable = ['download_id', 'locale', 'title', 'description'];

    public function download(): BelongsTo
    {
        return $this->belongsTo(Download::class);
    }
}
