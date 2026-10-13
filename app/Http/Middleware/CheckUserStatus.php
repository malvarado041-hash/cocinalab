<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        $user = Auth::user();

        if ($user->isPending()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está pendiente de aprobación por un administrador.');
        }

        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        // Acepta uno o varios roles separados por coma: role:admin,almacen
        // admin y sistemas siempre pasan cuando se pide un rol concreto.
        if (!empty($roles)) {
            $allowed = [];
            foreach ($roles as $group) {
                foreach (explode(',', $group) as $r) {
                    $r = trim($r);
                    if ($r !== '') {
                        $allowed[] = $r;
                    }
                }
            }
            $hasAccess = in_array($user->role, $allowed, true)
                || $user->isAdmin()
                || $user->isSistemas();

            if (!$hasAccess) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }
        }

        return $next($request);
    }
}
