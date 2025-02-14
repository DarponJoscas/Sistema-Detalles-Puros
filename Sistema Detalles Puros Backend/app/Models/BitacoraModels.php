<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'bitacora';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'id_pedido',
        'usuario',
        'descripcion',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_bitacora',
        'created_at',
        'updated_at',
    ];

    // Relación con la tabla detalle_pedido (uno a muchos)
    public function detallePedido()
    {
        return $this->belongsTo(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    // Relación con la tabla usuarios (uno a muchos)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'usuario');
    }

    // Timestamps automáticos
    public $timestamps = true;
}
