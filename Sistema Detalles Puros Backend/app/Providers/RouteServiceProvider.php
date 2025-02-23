<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La ruta a la que se redirige después del login
     */
    public const HOME = '/dashboard';

    /**
     * Define tus enlaces de rutas para la aplicación.
     */
    public function boot(): void
    {
        // Configurar los límites de tasa para la API
        $this->configureRateLimiting();

        $this->routes(function () {
            // Rutas de API
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rutas Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configura los límites de tasa para la aplicación.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Puedes agregar más limitadores personalizados aquí
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }

    /**
     * Define si las rutas son cacheadas para la aplicación.
     */
    protected function shouldCache(): bool
    {
        return app()->isProduction();
    }
}