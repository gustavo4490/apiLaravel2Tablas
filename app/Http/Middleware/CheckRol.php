<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CheckRol
{
    public function handle(Request $request, Closure $next, $rol)
    {
        if (! $request->user() || $request->user()->rol !== $rol) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        return $next($request);
    }
}
