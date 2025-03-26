<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Usuario;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $id_usuario = Auth::user()->id_usuario;

                $usuario = Usuario::with('rol')->find($id_usuario);

                if ($usuario) {
                    $id_rol = $usuario->rol ? $usuario->rol->id_rol : null;

                    $view->with([
                        'id_usuario' => $id_usuario,
                        'name_usuario' => $usuario->name_usuario,
                        'id_rol' => $id_rol,
                    ]);
                }
            }
        });

    }
}
