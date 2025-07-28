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
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->id();
            $table->string('nomestab');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->foreignId('id_ciudad')->constrained('ciudad');
            $table->timestamps();
        });
        DB::table('establecimiento')->insert(array('nomestab'=>'DIRECCION NACIONAL SALUD Y BIENESTAR SOCIAL','direccion'=>'','telefono'=>'','id_ciudad'=>1));
        DB::table('establecimiento')->insert(array('nomestab'=>'CLINICA DENTAL','direccion'=>'','telefono'=>'','id_ciudad'=>1));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO EL ALTO','direccion'=>'','telefono'=>'','id_ciudad'=>2));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO ORURO','direccion'=>'','telefono'=>'','id_ciudad'=>7));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO POTOSÍ','direccion'=>'','telefono'=>'','id_ciudad'=>8));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO PANDO','direccion'=>'','telefono'=>'','id_ciudad'=>6));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO BENI','direccion'=>'','telefono'=>'','id_ciudad'=>5));
        DB::table('establecimiento')->insert(array('nomestab'=>'HOSPITAL POLICIAL N3 SANTA CRUZ','direccion'=>'','telefono'=>'','id_ciudad'=>4));
        DB::table('establecimiento')->insert(array('nomestab'=>'HOSPITAL POLICIAL N2 CBBA','direccion'=>'','telefono'=>'','id_ciudad'=>3));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO TARIJA','direccion'=>'','telefono'=>'','id_ciudad'=>10));
        DB::table('establecimiento')->insert(array('nomestab'=>'POLICONSULTORIO CHUQUISACA','direccion'=>'','telefono'=>'','id_ciudad'=>9));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establecimiento');
    }
};
