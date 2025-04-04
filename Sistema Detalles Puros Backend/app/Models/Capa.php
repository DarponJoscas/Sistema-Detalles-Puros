<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_capa
 * @property string $capa
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Capa extends Model
{
    use HasFactory;
    protected $table = 'capa';
    protected $primaryKey = 'id_capa';
    protected $keyType = 'int';
    public $incrementing = false;

    protected $fillable = [
       'id_capa',
       'capa',
       'estado_capa',
    ];

    public $timestamps = true;
}
