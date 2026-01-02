<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = config('app.supported_locales');
        $locale = null;

        if (session()->has('locale')) {
            $locale = session('locale');
        }

        if (!$locale && Auth::check() && Auth::user()->language) {
            $locale = Auth::user()->language;
        }

        if (!$locale) {
            $locale = $request->getPreferredLanguage($supported);
        }

        if (!in_array($locale, $supported)) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        return $next($request);
    }
}
