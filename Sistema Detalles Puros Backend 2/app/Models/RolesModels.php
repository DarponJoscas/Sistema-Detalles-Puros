<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'roles';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'rol',
        'estado_rol',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_rol',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
