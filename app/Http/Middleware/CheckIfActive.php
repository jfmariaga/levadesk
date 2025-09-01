<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfActive
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->estado == 0) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta fue desactivada por un administrador.'
            ]);
        }

        return $next($request);
    }
}
