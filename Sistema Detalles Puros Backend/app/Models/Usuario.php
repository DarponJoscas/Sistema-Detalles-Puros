<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
/**
 * @property int $id_usuario
 * @property string $name_usuario
 * @property string $password
 * @property int $estado_usuario
 * @property int $id_rol
 */
class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'name_usuario',
        'password',
        'estado_usuario',
        'id_rol'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    public function getUserName()
    {
        return $this->name_usuario;
    }
}
