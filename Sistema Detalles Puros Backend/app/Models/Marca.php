<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_marca
 * @property string $marca
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Marca extends Model
{
    use HasFactory;
    protected $table = 'marca';
    protected $primaryKey = 'id_marca';
    public $incrementing = false;

    protected $fillable = [
        'id_marca',
        'marca',
        'estado_marca',
    ];

    public $timestamps = true;
}
