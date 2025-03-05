<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_rol
 * @property string $rol
 * @property bool $estado_rol
 */
class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    protected $fillable = [
        'rol',
        'estado_rol',
    ];

    public $timestamps = true;
}
