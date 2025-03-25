<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // El nombre de la tabla en la base de datos
    protected $table = 'sessions';

    // La clave primaria de la tabla
    protected $primaryKey = 'id';

    // Para evitar la gestión automática de timestamps (created_at, updated_at)
    public $timestamps = false;

    // Los atributos que son asignables
    protected $fillable = [
        'id',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity'
    ];

    // Definir la relación con el modelo Usuario (suponiendo que la tabla usuarios existe)
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }

    // Si deseas convertir el campo 'payload' en un array o JSON, puedes usar cast
    protected $casts = [
        'payload' => 'array',
    ];
}
