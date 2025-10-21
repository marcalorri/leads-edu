<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromBrowser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = config('app.available_locales', ['en']);

        // Check if user has a preferred locale in session
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            if (in_array($locale, $availableLocales)) {
                App::setLocale($locale);
                return $next($request);
            }
        }

        // Get the Accept-Language header from the browser
        $browserLanguage = $request->getPreferredLanguage($availableLocales);

        // If a valid language is detected, set it
        if ($browserLanguage && in_array($browserLanguage, $availableLocales)) {
            App::setLocale($browserLanguage);
        }

        return $next($request);
    }
}
