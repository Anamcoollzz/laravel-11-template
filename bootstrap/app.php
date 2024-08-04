<?php

use App\Http\Middleware\EnsureAppKey;
use App\Http\Middleware\LogRequestMiddleware;
use App\Http\Middleware\OverrideConfig;
use App\Http\Middleware\ViewShare;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::prefix('api')
                ->middleware([OverrideConfig::class, 'api', LogRequestMiddleware::class,  EnsureAppKey::class,])
                ->group(base_path('routes/api.php'));

            Route::middleware([OverrideConfig::class, 'web', LogRequestMiddleware::class,  ViewShare::class,])
                ->group(base_path('routes/stisla-web.php'));

            Route::middleware([OverrideConfig::class, 'web', LogRequestMiddleware::class, ViewShare::class, 'auth',])
                ->group(base_path('routes/stisla-web-auth.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
