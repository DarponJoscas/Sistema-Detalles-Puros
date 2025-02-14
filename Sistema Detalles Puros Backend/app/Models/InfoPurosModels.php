<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPurosModels extends Model
{
    use HasFactory;

    // Definir la tabla relacionada
    protected $table = 'info_puro';

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'id_cliente',
        'id_presentacion',
        'id_marcas',
        'id_vitola',
        'id_aliasvitola',
        'id_capas',
        'id_empaque',
    ];

    // Definir los campos que no deben ser asignados masivamente
    protected $guarded = [
        'id_puro',
        'created_at',
        'updated_at',
    ];

    // Relación con la tabla clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    // Relación con la tabla presentaciones
    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'id_presentacion', 'id_presentacion');
    }

    // Relación con la tabla marcas
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marcas', 'id_marca');
    }

    // Relación con la tabla vitolas
    public function vitola()
    {
        return $this->belongsTo(Vitola::class, 'id_vitola', 'id_vitola');
    }

    // Relación con la tabla alias_vitola
    public function aliasVitola()
    {
        return $this->belongsTo(AliasVitola::class, 'id_aliasvitola', 'id_aliasvitola');
    }

    // Relación con la tabla capas
    public function capa()
    {
        return $this->belongsTo(Capa::class, 'id_capas', 'id_capa');
    }

    // Relación con la tabla tipo_empaque
    public function empaque()
    {
        return $this->belongsTo(TipoEmpaque::class, 'id_empaque', 'id_empaque');
    }

    // Timestamps automáticos
    public $timestamps = true;
}
