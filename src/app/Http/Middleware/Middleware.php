<?php

namespace Glare\Http\Middleware;

use Closure;

class Middleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
