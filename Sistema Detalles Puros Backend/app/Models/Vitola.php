<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id_vitola
 * @property string|null $vitola
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Vitola extends Model
{
    use HasFactory;
    protected $table = 'vitola';
    protected $primaryKey = 'id_vitola';
    protected $keyType = 'int';
    public $incrementing = false;

    protected $fillable = [
        'id_vitola',
        'vitola',
    ];

    public $timestamps = true;
}
