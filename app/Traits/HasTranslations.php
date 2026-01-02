<?php

namespace App\Traits;

trait HasTranslations
{
    protected function translate(string $translationsField, string $fallbackField): string
    {
        $translations = $this->{$translationsField};

        if (is_array($translations)) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale');

            if (!empty($translations[$locale])) {
                return $translations[$locale];
            }

            if (!empty($translations[$fallbackLocale])) {
                return $translations[$fallbackLocale];
            }
        }

        return $this->{$fallbackField};
    }
}
