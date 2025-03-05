<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empaque
 *
 * @property int $id_empaque
 * @property string|null $tipo_empaque
 * @property string|null $descripcion_empaque
 * @property string|null $imagen_anillado
 * @property string|null $imagen_caja
 * @property string|null $cantidad_caja
 * @property bool $estado_empaque
 * @property string|null $created_at
 * @property string|null $updated_at
 */

class Empaque extends Model
{
    use HasFactory;

    protected $table = 'empaque';

    protected $primaryKey = 'id_empaque';

    public $timestamps = true;

    protected $fillable = [
        'codigo_empaque',
        'tipo_empaque',
        'descripcion_empaque',
        'imagen_anillado',
        'imagen_caja',
        'cantidad_caja',
        'estado_empaque',
        'created_at',
        'updated_at',
    ];

    public $codigo_empaque;
    public $tipo_empaque;
    public $descripcion_empaque;
    public $imagen_anillado;
    public $imagen_caja;
    public $cantidad_caja;
    public $estado_empaque;
}
