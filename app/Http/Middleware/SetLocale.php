<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the visitor's chosen UI language (see LocaleController) for the
 * rest of the request. Falls back to config('app.locale') for anyone who
 * hasn't picked one, or picked something not in config('regency.locales').
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        if (! array_key_exists($locale, config('regency.locales'))) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
