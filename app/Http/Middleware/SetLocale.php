<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is in the request (from route or query parameter)
        $locale = $request->route('locale') ?? $request->query('lang');
        
        // If not in request, check session
        if (!$locale) {
            $locale = Session::get('locale');
        }
        
        // If still no locale, check user preference or default
        if (!$locale) {
            $locale = config('app.locale', 'en');
        }
        
        // Validate locale (only allow 'en' or 'ar')
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        
        // Set the locale
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}

