<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id(); // crea una columna 'id' con autoincremento
            $table->string('descripcion'); // columna para la descripción
            $table->foreignId('usuario_id')->constrained('usuarios','id_usuario'); // clave foránea a la tabla 'usuarios'
            $table->timestamps(); // crea las columnas created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacora');
    }
}
