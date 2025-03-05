<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoTable extends Migration
{
    public function up()
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->foreignId('id_puro')->constrained('info_puro', 'id_puro');
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente');
            $table->string('descripcion_produccion', 255)->nullable();
            $table->string('imagen_produccion', 255)->nullable();
            $table->foreignId('id_empaque')->constrained('tipo_empaque', 'id_empaque')->nullable();
            $table->string('descripcion_empaque', 255)->nullable();
            $table->string('imagen_anillado', 255)->nullable();
            $table->string('imagen_caja', 255)->nullable();
            $table->string('cantidad_caja', 255)->nullable();
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalle_pedido');
    }
}
