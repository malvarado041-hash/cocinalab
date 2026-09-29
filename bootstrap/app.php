<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Railway termina TLS en su proxy: confiar en él para que
        // asset() y url() generen https:// y no haya contenido mixto.
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Excepciones
    })->create();
