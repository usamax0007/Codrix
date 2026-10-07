<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfWww
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') === 'production' && str_starts_with($request->getHost(), 'www.')) {
            return redirect(str_replace('www.', '', $request->fullUrl()), 301);
        }

        return $next($request);
    }
}
