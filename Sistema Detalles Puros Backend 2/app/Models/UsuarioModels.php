<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class UsuarioModels extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'usuario';
    public $incrementing = false;
    protected $fillable = ['usuario', 'id_rol', 'contrasena_usuario', 'estado_usuario'];
    public $timestamps = true;
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
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
