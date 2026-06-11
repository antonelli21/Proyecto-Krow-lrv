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
    ->withMiddleware(function (Middleware $middleware): void {
        // ═══════════════════════════════════════════════════════════
        // Registrar alias de middleware personalizados.
        // Estos alias se usan en las rutas con ->middleware('alias')
        // ═══════════════════════════════════════════════════════════

        $middleware->alias([
            // Middleware que verifica que el email del usuario esté verificado
            // Uso: ->middleware('verified')
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,

            // Middleware que verifica el rol del usuario (estudiante, empresa, admin)
            // Uso: ->middleware('role:estudiante') o ->middleware('role:admin,empresa')
            'role' => \App\Http\Middleware\CheckRole::class,

            // Middleware que redirige a usuarios ya logueados (para login/registro)
            // Uso: ->middleware('guest')
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
