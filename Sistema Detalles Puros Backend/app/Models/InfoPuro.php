<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_puro
 * @property string $presentacion_puro
 * @property string $marca_puro
 * @property string $alias_vitola
 * @property string $vitola
 * @property string $capa_puro
 * @property string $codigo_puro
 */

class InfoPuro extends Model
{
    use HasFactory;

    protected $table = 'info_puro';
    protected $primaryKey = 'codigo_puro';

    protected $fillable = [
        'presentacion_puro',
        'marca_puro',
        'alias_vitola',
        'vitola',
        'capa_puro',
        'codigo_puro',
        'estado_puro',
    ];

    public $timestamps = true;
}
