<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoPuroTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('info_puro', function (Blueprint $table) {
            $table->id('id_puro');
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->string('codigo_puro', 30);
            $table->foreignId('id_presentacion')->constrained('presentaciones', 'id_presentacion');
            $table->foreignId('id_marca')->constrained('marcas', 'id_marca');
            $table->foreignId('id_vitola')->constrained('vitolas', 'id_vitola');
            $table->foreignId('id_aliasvitola')->constrained('alias_vitola', 'id_aliasvitola');
            $table->foreignId('id_capa')->constrained('capas', 'id_capa');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_puro');
    }
};
