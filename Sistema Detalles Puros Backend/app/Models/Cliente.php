<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cliente
 *
 * @property int $id_cliente
 * @property string $name_cliente
 * @property bool $estado_cliente
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    protected $fillable = ['name_cliente', 'estado_cliente'];
    public $timestamps = true;
}
