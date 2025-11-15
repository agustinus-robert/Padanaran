<?php

namespace Modules\Portal\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (isLevelOne($user)) {
            return $next($request);
        } 
        
        if(isSuperUser($user)){
            if (!session()->has('selected_grade')) {
                return redirect()->route('choose.education');
            }

            return $next($request);
        }


        if (!Gate::allows('portal::access')) {
            if ($request->user()?->student) {
                return redirect()->route('academic::home');
            }
        }

        if (Gate::forUser($request->user())->allows('is-casier')) {
            return redirect()->route('portal::dashboard.index');
        }

        if (Gate::forUser($request->user())->allows('is-supplier')) {
            return redirect()->route('poz::supplier.dashboard');
        }

        return $next($request);
    }
}