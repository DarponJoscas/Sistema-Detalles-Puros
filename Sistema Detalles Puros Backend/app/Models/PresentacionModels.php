<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'presentaciones';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'name_presentacion',
        'estado_presentacion',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_presentacion',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
