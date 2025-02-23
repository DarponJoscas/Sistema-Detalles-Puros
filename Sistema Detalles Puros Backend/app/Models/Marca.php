<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Marca
 *
 * @property int $id_marca
 * @property string $name_marca
 * @property bool $estado_marca
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'id_marca';
    protected $fillable = ['name_marca', 'estado_marca'];
    public $timestamps = true;
}
