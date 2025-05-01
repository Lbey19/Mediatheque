<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'employee'])) {
            return $next($request);
        }

        abort(403, 'Accès refusé.');
    }
}
