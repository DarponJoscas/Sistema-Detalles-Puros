<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Capas
 *
 * @property int $id_capa
 * @property string $tipo_capa
 * @property bool $estado_capa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Capas extends Model
{
    protected $table = 'capas';
    protected $primaryKey = 'id_capa';
    protected $fillable = ['tipo_capa', 'estado_capa'];
    public $timestamps = true;
}
