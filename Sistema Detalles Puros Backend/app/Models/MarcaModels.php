<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'marcas';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'name_marca',
        'estado_marca',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_marca',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
