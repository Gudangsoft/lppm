<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengabdianTranslation extends Model
{
    protected $fillable = ['pengabdian_id', 'locale', 'title', 'abstract', 'content', 'output'];

    public function pengabdian(): BelongsTo
    {
        return $this->belongsTo(Pengabdian::class);
    }
}
