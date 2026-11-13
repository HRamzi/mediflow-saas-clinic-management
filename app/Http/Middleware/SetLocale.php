<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = ['en', 'fr', 'ar'];

        $header = $request->header('Accept-Language', 'en');

        $locale = substr($header, 0, 2);

        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}