<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InfoPuro
 *
 * @property int $id_puro
 * @property int $id_cliente
 * @property string $codigo_pedido
 * @property int $id_presentacion
 * @property int $id_marcas
 * @property int $id_vitola
 * @property int $id_aliasvitola
 * @property int $id_capas
 * @property int $id_empaque
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Cliente $cliente
 * @property-read \App\Models\Presentacion $presentacion
 * @property-read \App\Models\Marca $marca
 * @property-read \App\Models\Vitola $vitola
 * @property-read \App\Models\AliasVitola $aliasVitola
 * @property-read \App\Models\Capa $capa
 * @property-read \App\Models\TipoEmpaque $empaque
 */

class InfoPuro extends Model
{
    protected $table = 'info_puro';
    protected $primaryKey = 'id_puro';
    protected $fillable = ['id_cliente', 'codigo_pedido', 'id_presentacion', 'id_marcas', 'id_vitola', 'id_aliasvitola', 'id_capas', 'id_empaque'];
    public $timestamps = true;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'id_presentacion');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marcas');
    }

    public function vitola()
    {
        return $this->belongsTo(Vitola::class, 'id_vitola');
    }

    public function aliasVitola()
    {
        return $this->belongsTo(AliasVitola::class, 'id_aliasvitola');
    }

    public function capa()
    {
        return $this->belongsTo(Capa::class, 'id_capas');
    }

    public function empaque()
    {
        return $this->belongsTo(TipoEmpaque::class, 'id_empaque');
    }
}
