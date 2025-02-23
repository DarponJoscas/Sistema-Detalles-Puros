<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TipoEmpaque
 *
 * @property int $id_empaque
 * @property string $tipo_empaque
 * @property bool $estado_empaque
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class TipoEmpaque extends Model
{
    protected $table = 'tipo_empaque';
    protected $primaryKey = 'id_empaque';
    protected $fillable = ['tipo_empaque', 'estado_empaque'];
    public $timestamps = true;
}
