<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
    {
        $user = Auth::guard('usuarios')->user();

        if (!$user || $user->rol != $type) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
