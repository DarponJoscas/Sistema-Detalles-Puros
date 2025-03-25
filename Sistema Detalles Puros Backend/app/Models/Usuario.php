<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'usuarios'; // Asegúrate de que coincida con tu tabla
    protected $primaryKey = 'id_usuario'; // Si la columna de la clave primaria tiene un nombre diferente

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_usuario',
        'password',
        'estado_usuario',
        'id_rol',
        // Otros campos que puedas tener
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado_usuario' => 'boolean',
        'id_rol' => 'integer',
    ];

    /**
     * Get the role that owns the user.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    // Implementación de los métodos requeridos por JWTSubject

    /**
     * Obtiene el identificador del JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Usualmente devuelve el ID del usuario
    }

    /**
     * Obtiene las afirmaciones (claims) personalizadas del JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return []; // Puedes agregar datos personalizados aquí si lo deseas
    }
}
