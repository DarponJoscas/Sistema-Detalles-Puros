<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_bitacora
 * @property string $descripcion
 * @property int $id_usuario
 */
class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';

    protected $primaryKey = 'id_bitacora';

    protected $fillable = [
        'descripcion',
        'accion',
        'id_usuario',
    ];

    public $timestamps = true;
}
