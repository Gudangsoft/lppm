<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderTranslation extends Model
{
    protected $fillable = ['slider_id', 'locale', 'title', 'subtitle', 'button_text'];

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
