<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PkmProgramTranslation extends Model
{
    protected $fillable = ['pkm_program_id', 'locale', 'name', 'description'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(PkmProgram::class, 'pkm_program_id');
    }
}
