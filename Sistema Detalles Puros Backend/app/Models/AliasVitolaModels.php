<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AliasVitolaModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'alias_vitola';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'alias_vitola',
        'estado_aliasvitola',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_aliasvitola',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
