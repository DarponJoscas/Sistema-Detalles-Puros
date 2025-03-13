<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_aliasvitola
 * @property string $alias_vitola
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AliasVitola extends Model
{
    use HasFactory;
    protected $table = 'alias_vitola';
    protected $primaryKey = 'id_aliasvitola';
    protected $fillable = ['alias_vitola'];
    public $timestamps = true;
}
