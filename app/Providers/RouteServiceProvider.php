<?php

namespace App\Providers;

use App\Http\Middleware\EnsureAppKey;
use App\Http\Middleware\OverrideConfig;
use App\Http\Middleware\ViewShare;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware([OverrideConfig::class, 'api', EnsureAppKey::class,])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/web.php'));

            Route::middleware([OverrideConfig::class, 'web', ViewShare::class,])
                ->namespace($this->namespace)
                ->group(base_path('routes/stisla-web.php'));

            Route::middleware([OverrideConfig::class, 'web', ViewShare::class, 'auth',])
                ->namespace($this->namespace)
                ->group(base_path('routes/stisla-web-auth.php'));
        });

        $this->setupPerModule();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function setupPerModule()
    {
        $filePaths = base_path('routes/modules');
        $files = getFileNamesFromDir($filePaths);

        foreach ($files as $file) {
            $path = base_path('routes/modules/' . $file);
            Route::namespace($this->namespace)
                ->group($path);
        }
    }
}
