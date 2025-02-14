<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedidoModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'detalle_pedido';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'id_puro',
        'codigo_pedido',
        'descripcion_produccion',
        'imagen_produccion',
        'descripcion_empaque',
        'imagen_anillado',
        'imagen_caja',
        'cantidad_caja',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_pedido',
        'created_at',
        'updated_at',
    ];

    // Relación con la tabla info_puro (uno a muchos)
    public function infoPuro()
    {
        return $this->belongsTo(InfoPuro::class, 'id_puro', 'id_puro');
    }

    // Timestamps automáticos
    public $timestamps = true;
}
