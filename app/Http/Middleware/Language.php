<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->cookie('language') && array_key_exists($request->cookie('language'), Config::get('languages'))) {
            App::setLocale($request->cookie('language'));
        }

        return $next($request);
    }
}
