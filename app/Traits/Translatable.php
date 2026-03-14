<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Translatable
{
    public function translations(): HasMany
    {
        return $this->hasMany($this->getTranslationModelName());
    }

    public function translation(): HasOne
    {
        $locale = app()->getLocale();
        return $this->hasOne($this->getTranslationModelName())
            ->where('locale', $locale)
            ->withDefault(function () use ($locale) {
                return $this->translations()->where('locale', config('app.fallback_locale'))->first() 
                    ?? new ($this->getTranslationModelName())();
            });
    }

    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()
            ?? $this->translations()->where('locale', config('app.fallback_locale'))->first();
    }

    public function translateOrNew($locale)
    {
        return $this->translations()->firstOrNew(['locale' => $locale]);
    }

    protected function getTranslationModelName(): string
    {
        return static::class . 'Translation';
    }

    public function __get($key)
    {
        // First check if it's a regular attribute
        $value = parent::__get($key);
        if ($value !== null) {
            return $value;
        }

        // Check if it's a translatable attribute
        if (isset($this->translatable) && in_array($key, $this->translatable)) {
            $translation = $this->getTranslation();
            return $translation ? $translation->$key : null;
        }

        return null;
    }
}
