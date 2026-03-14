<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, or use default
        $locale = session('locale');

        if (!$locale) {
            $defaultLanguage = Language::getDefault();
            $locale = $defaultLanguage ? $defaultLanguage->code : config('app.locale');
        }

        // Validate locale exists
        $activeLanguages = Language::getActive()->pluck('code')->toArray();
        if (!in_array($locale, $activeLanguages) && !empty($activeLanguages)) {
            $locale = $activeLanguages[0];
        }

        App::setLocale($locale);
        session(['locale' => $locale]);

        // Share active languages with all views
        View::share('activeLanguages', Language::getActive());
        View::share('currentLocale', $locale);

        return $next($request);
    }
}
