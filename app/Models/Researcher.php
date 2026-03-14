<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Researcher extends Model
{
    protected $fillable = [
        'user_id', 'name', 'nidn', 'nip', 'email', 'phone',
        'department', 'faculty', 'photo', 'bio',
        'scopus_id', 'google_scholar_id', 'orcid', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function researches(): BelongsToMany
    {
        return $this->belongsToMany(Research::class, 'research_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function pengabdians(): BelongsToMany
    {
        return $this->belongsToMany(Pengabdian::class, 'pengabdian_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function publications()
    {
        return $this->hasMany(PublicationAuthor::class);
    }

    public function internalGrantSubmissions()
    {
        return $this->hasMany(InternalGrantSubmission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
