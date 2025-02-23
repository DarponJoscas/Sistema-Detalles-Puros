<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DetallePedido
 *
 * @property int $id_pedido
 * @property int $id_puro
 * @property string $descripcion_produccion
 * @property string|null $imagen_produccion
 * @property string $descripcion_empaque
 * @property string|null $imagen_anillado
 * @property string|null $imagen_caja
 * @property int $cantidad_caja
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\InfoPuro $infoPuro
 */

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_pedido';
    protected $fillable = ['id_puro', 'descripcion_produccion', 'imagen_produccion', 'descripcion_empaque', 'imagen_anillado', 'imagen_caja', 'cantidad_caja'];
    public $timestamps = true;

    public function infoPuro()
    {
        return $this->belongsTo(InfoPuro::class, 'id_puro');
    }
}
