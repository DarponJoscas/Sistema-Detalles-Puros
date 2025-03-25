<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpaque extends Model
{
    use HasFactory;

    protected $table = 'tipo_empaques';

    protected $primaryKey = 'id_tipoempaque';

    protected $fillable = [
        'tipo_empaque',
        'estado_tipoEmpaque',
    ];

    public $timestamps = true;

}
