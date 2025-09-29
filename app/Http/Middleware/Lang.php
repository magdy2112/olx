<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            $locale = $request->header('accept-language');
             if(in_array($locale, ['en', 'ar'])) {
                 App::setLocale($locale);
             } else {
                 App::setLocale('en'); // Default to English if not supported
             }
        return $next($request);
    }
}
