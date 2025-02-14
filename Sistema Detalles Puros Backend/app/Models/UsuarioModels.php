<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UsuarioModels extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios'; // Nombre de la tabla en BD
    protected $primaryKey = 'usuario'; // Clave primaria
    public $incrementing = false; // Evita que Laravel asuma que la PK es autoincremental
    protected $fillable = ['usuario', 'id_rol', 'contrasena_usuario', 'estado_usuario'];
    protected $hidden = ['contrasena_usuario'];

    public function getAuthPassword()
    {
        return $this->contrasena_usuario;
    }

    public function setContrasenaUsuarioAttribute($value)
    {
        $this->attributes['contrasena_usuario'] = Hash::make($value);
    }

    public function getJWTIdentifier()
    {
        return $this->usuario;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function rol()
    {
        return $this->belongsTo(RolesModels::class, 'id_rol', 'id_rol');
    }
}
