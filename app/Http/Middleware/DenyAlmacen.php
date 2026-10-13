<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloquea al rol almacen fuera de sus pantallas.
 * El usuario con rol almacen solo puede usar los módulos de almacén y
 * proveedores; cualquier otro intento lo redirige a admin.almacen.index.
 */
class DenyAlmacen
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isAlmacen()) {
            // Si es petición AJAX/fetch, responde 403 en vez de redirect HTML.
            if ($request->ajax() || $request->wantsJson()) {
                abort(403, 'Tu rol solo tiene acceso al módulo de almacén.');
            }

            return redirect()->route('admin.almacen.index')
                ->with('error', 'Tu rol solo tiene acceso al módulo de almacén.');
        }

        return $next($request);
    }
}
