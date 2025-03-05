<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('info_puro', function (Blueprint $table) {
            $table->id('id_puro');
            $table->string('presentacion_puro', 50);
            $table->string('marca_puro', 50);
            $table->string('alias_vitola', 50);
            $table->string('vitola', 10);
            $table->string('capa_puro', 40);
            $table->string('codigo_puro', 7);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('info_puro');
    }
}
