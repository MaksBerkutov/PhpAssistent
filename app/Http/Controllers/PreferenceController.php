<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    private const ALLOWED_LOCALES = ['en', 'ru'];
    private const ALLOWED_THEMES = ['light', 'dark'];

    public function setLocale(string $locale, Request $request): RedirectResponse
    {
        if (!in_array($locale, self::ALLOWED_LOCALES, true)) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }

    public function setTheme(string $theme, Request $request): RedirectResponse
    {
        if (!in_array($theme, self::ALLOWED_THEMES, true)) {
            abort(404);
        }

        $request->session()->put('theme', $theme);

        return redirect()->back();
    }
}
