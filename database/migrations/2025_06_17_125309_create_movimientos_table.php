<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad_utilizada', 10, 2);
            $table->decimal('precio_unitario', 10, 2); // precio histórico para trazabilidad
            $table->foreignId('id_salida')->constrained('salidas');
            $table->foreignId('id_entrada')->constrained('entradas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos');
    }
};
