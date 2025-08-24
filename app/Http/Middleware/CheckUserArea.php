<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserArea
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ignorar si es una petición Livewire (AJAX interna)
        if ($request->expectsJson() || $request->header('X-Livewire')) {
            return $next($request);
        }

        // Si el usuario está autenticado y no ha seleccionado área
        if (Auth::check() && is_null(Auth::user()->area)) {
            if (!$request->is('seleccionar-area')) {
                return redirect()->route('seleccionar.area');
            }
        }

        return $next($request);
    }
}
