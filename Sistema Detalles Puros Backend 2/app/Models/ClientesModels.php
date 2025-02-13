<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'clientes';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'name_cliente',
        'estado_cliente',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_cliente',
        'created_at',
        'updated_at',
    ];

    // Timestamps automáticos
    public $timestamps = true;
}
