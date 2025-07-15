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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->integer('restante'); 
            $table->integer('numero_anual');
            $table->integer('anio');
            $table->foreignId('id_articulo')->constrained('articulos');
            $table->foreignId('id_personal')->constrained('personal'); 
            $table->foreignId('id_proveedor')->constrained('provedores'); 
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
        Schema::dropIfExists('entradas');
    }
};
