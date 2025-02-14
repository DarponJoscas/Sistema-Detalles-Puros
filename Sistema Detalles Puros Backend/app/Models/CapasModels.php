<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapasModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'capas';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'tipo_capa',
        'estado_capa',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_capa',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
