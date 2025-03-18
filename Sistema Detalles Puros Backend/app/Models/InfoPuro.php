<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_puro
 * @property string $codigo_puro
 * @property string $presentacion_puro
 * @property int $id_marca
 * @property int $id_vitola
 * @property int $id_aliasvitola
 * @property int $id_capa
 * @property int|null $estado_puro
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class InfoPuro extends Model
{
    use HasFactory;
    protected $table = 'info_puro';
    protected $primaryKey = 'id_puro';
    protected $keyType = 'int';
    public $incrementing = false;

    protected $fillable = [
         'id_puro', 'codigo_puro', 'presentacion_puro', 'id_marca', 'id_vitola',
        'id_aliasvitola', 'id_capa', 'estado_puro'
    ];
    public $timestamps = true;

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    public function vitola()
    {
        return $this->belongsTo(Vitola::class, 'id_vitola');
    }

    public function aliasVitola()
    {
        return $this->belongsTo(AliasVitola::class, 'id_aliasvitola');
    }

    public function capa()
    {
        return $this->belongsTo(Capa::class, 'id_capa');
    }
}
