<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bitacora
 *
 * @property int $id_bitacora
 * @property int $id_pedido
 * @property int $usuario
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\DetallePedido $detallePedido
 * @property-read \App\Models\Usuario $usuario
 */

class Bitacora extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'id_bitacora';
    protected $fillable = ['id_pedido', 'usuario', 'descripcion'];
    public $timestamps = true;

    public function detallePedido()
    {
        return $this->belongsTo(DetallePedido::class, 'id_pedido');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario');
    }
}
