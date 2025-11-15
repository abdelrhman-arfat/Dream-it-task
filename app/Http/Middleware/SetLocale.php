<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get Accept-Language header
        $locale = $request->header('Accept-Language');

        // Supported locales
        $supportedLocales = ['en', 'ar'];

        // Use Accept-Language if supported, otherwise use default app locale
        if ($locale && in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
        } else {
            App::setLocale(session("locale", config("app.locale"))); // fallback to default
        }

        // Set locale in session
        session(['locale' => App::getLocale()]);

        return $next($request);
    }
}
