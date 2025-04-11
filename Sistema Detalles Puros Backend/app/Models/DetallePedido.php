<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallePedido
 *
 * @property int $id_pedido
 * @property int $id_puro
 * @property int $id_cliente
 * @property int|null $id_empaque
 * @property string|null $descripcion_produccion
 * @property string|null $imagen_produccion
 * @property int|null $estado_pedido
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property-read \App\Models\Cliente $cliente
 * @property-read \App\Models\InfoPuro $puro
 * @property-read \App\Models\Empaque|null $empaque
 */
class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedido';

    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_puro',
        'id_cliente',
        'id_empaque',
        'descripcion_produccion',
        'estado_pedido',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function puro()
    {
        return $this->belongsTo(InfoPuro::class, 'id_puro');
    }

    public function empaque()
    {
        return $this->belongsTo(InfoEmpaque::class, 'id_empaque');
    }
}
