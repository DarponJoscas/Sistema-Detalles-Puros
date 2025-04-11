<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialImagen extends Model
{
    use HasFactory;

    protected $table = 'historial_imagenes';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_pedido',
        'imagen_produccion',
        'imagen_anillado',
        'imagen_caja',
        'fecha_cambio',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(DetallePedido::class, 'id_pedido');
    }
}
