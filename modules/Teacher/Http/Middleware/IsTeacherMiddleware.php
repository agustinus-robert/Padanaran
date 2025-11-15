<?php

namespace Modules\Teacher\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class IsTeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $classTeacher = FacadesAuth::user()->employee->classroom;
    
        return Gate::authorize('teacher::access') && $classTeacher
            ? $next($request)
            : redirect()->back()->with('danger', 'Anda tidak memiliki akses ke tautan tersebut');
    }
}
