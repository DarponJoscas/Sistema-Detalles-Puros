<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Presentacione
 *
 * @property int $id_presentacion
 * @property string $name_presentacion
 * @property bool $estado_presentacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Presentacione extends Model
{
    protected $table = 'presentaciones';
    protected $primaryKey = 'id_presentacion';
    protected $fillable = ['name_presentacion', 'estado_presentacion'];
    public $timestamps = true;
}
