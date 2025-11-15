<?php

namespace Modules\Finance\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        return Gate::authorize('finance::access')
            ? $next($request)
            : abort(403);
    }
}
