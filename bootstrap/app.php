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
        
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);

        // 🌟 AGREGA ESTA LÍNEA JUSTO ACÁ ABAJO:
        $middleware->redirectTo(
            guests: '/login',
            users: '/inicio'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
