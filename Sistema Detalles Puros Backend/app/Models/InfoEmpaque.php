<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoEmpaque extends Model
{
    use HasFactory;

    protected $table = 'info_empaque';

    protected $primaryKey = 'id_empaque';
    protected $keyType = 'varchar';
    public $incrementing = false;

    protected $fillable = [
        'id_empaque',
        'codigo_empaque',
        'id_puro',
        'sampler',
        'id_tipoempaque',
        'descripcion_empaque',
        'anillo',
        'imagen_anillado',
        'sello',
        'upc',
        'codigo_caja',
        'imagen_caja',
        'estado_empaque',
    ];

    public $timestamps = true;

    public function tipoEmpaque()
    {
        return $this->belongsTo(TipoEmpaque::class, 'id_tipoempaque');
    }

    public function infoPuro()
    {
        return $this->belongsTo(InfoPuro::class, 'id_puro');
    }
}
