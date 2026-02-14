<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario es admin (rol_id = 1 o similar)
        // Ajusta esto según tu implementación de roles
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
