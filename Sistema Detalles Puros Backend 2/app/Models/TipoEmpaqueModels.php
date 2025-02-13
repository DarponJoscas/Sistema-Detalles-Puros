<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpaque extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'tipo_empaque';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'tipo_empaque',
        'estado_empaque',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_empaque',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
