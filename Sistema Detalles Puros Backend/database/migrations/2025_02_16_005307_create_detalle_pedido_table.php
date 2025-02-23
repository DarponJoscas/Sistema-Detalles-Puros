<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->foreignId('id_puro')->constrained('info_puro', 'id_puro');
            $table->string('descripcion_produccion', 255)->nullable();
            $table->string('imagen_produccion', 255)->nullable();
            $table->foreignId('id_empaque')->constrained('tipo_empaque', 'id_empaque');
            $table->string('descripcion_empaque', 255)->nullable();
            $table->string('imagen_anillado', 255)->nullable();
            $table->string('imagen_caja', 255)->nullable();
            $table->string('cantidad_caja', 255)->nullable();
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
