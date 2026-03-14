<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationAuthor extends Model
{
    protected $fillable = [
        'publication_id', 'researcher_id', 'name', 'affiliation', 'order', 'is_corresponding'
    ];

    protected $casts = [
        'is_corresponding' => 'boolean',
    ];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function researcher(): BelongsTo
    {
        return $this->belongsTo(Researcher::class);
    }
}
