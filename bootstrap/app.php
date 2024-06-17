<?php

use App\Http\Middleware\IsVerifyEmail;
use Illuminate\Foundation\Application;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_verify_email' => IsVerifyEmail::class,
            'can' => RoleMiddleware::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        // $middleware->api(append: [
        //     CorsMiddleware::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();