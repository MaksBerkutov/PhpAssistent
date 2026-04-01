<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserPreferences
{
    private const ALLOWED_LOCALES = ['en', 'ru'];
    private const ALLOWED_THEMES = ['light', 'dark'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        if (!in_array($locale, self::ALLOWED_LOCALES, true)) {
            $locale = config('app.fallback_locale', 'en');
            $request->session()->put('locale', $locale);
        }

        app()->setLocale($locale);

        $theme = $request->session()->get('theme', 'light');

        if (!in_array($theme, self::ALLOWED_THEMES, true)) {
            $request->session()->put('theme', 'light');
        }

        return $next($request);
    }
}
