<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Este es el espacio de nombres aplicado a los controladores de rutas.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define la ruta de inicio para autenticación de usuarios.
     */
    public const HOME = '/home';

    /**
     * Boot para registrar rutas y otros ajustes.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define las rutas de la aplicación.
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define las rutas de la API.
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // Prefijo para las rutas de API
            ->middleware('api') // Middleware para la API
            ->namespace($this->namespace) // Espacio de nombres
            ->group(base_path('routes/api.php')); // Archivo de rutas API
    }

    /**
     * Define las rutas web.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') // Middleware para las rutas web
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php')); // Archivo de rutas web
    }
}
