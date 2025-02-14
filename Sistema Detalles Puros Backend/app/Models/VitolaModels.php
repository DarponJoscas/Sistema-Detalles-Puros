<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitolaModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'vitolas';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'tipo_vitola',
        'estado_vitola',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_vitola',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
