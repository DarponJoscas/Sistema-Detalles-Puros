<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAliasVitolaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alias_vitola', function (Blueprint $table) {
            $table->id('id_aliasvitola');
            $table->string('alias_vitola', 50);
            $table->boolean('estado_aliasvitola');
            $table->timestamps(0); // Para manejar created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alias_vitola');
    }
};
