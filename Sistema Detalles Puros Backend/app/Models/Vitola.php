<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Vitola
 *
 * @property int $id_vitola
 * @property string $tipo_vitola
 * @property bool $estado_vitola
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Vitola extends model
{
    protected $table = 'vitolas';
    protected $primaryKey = 'id_vitola';
    protected $fillable = ['tipo_vitola', 'estado_vitola'];
    public $timestamps = true;
}
