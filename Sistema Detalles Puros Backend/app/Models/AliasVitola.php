<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AliasVitola
 *
 * @property int $id_aliasvitola
 * @property string $alias_vitola
 * @property bool $estado_aliasvitola
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class AliasVitola extends Model
{
    protected $table = 'alias_vitola';
    protected $primaryKey = 'id_aliasvitola';
    protected $fillable = ['alias_vitola', 'estado_aliasvitola'];
    public $timestamps = true;
}
