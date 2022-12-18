<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentry\Laravel\Tracing\Middleware;

class CatalogViewMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if($request->has('view')) {
            $request->session()->put('view', $request->get('view'));
        }

        return $next($request);
    }
}
