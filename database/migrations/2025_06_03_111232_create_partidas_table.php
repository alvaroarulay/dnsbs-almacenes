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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nompartida')->nullable();
            $table->timestamps();
        });
        DB::table('partidas')->insert(array('codigo'=>'256000','nompartida'=>'IMPRENTA'));
        DB::table('partidas')->insert(array('codigo'=>'311000','nompartida'=>'CARNES Y SUS DERIVADOS'));
        DB::table('partidas')->insert(array('codigo'=>'332000','nompartida'=>'CONFECCIONES TEXTILES'));
        DB::table('partidas')->insert(array('codigo'=>'333000','nompartida'=>'PRENDAS DE VESTIR'));
        DB::table('partidas')->insert(array('codigo'=>'341100','nompartida'=>'COMBUSTIBLES Y LUBRICANTES PARA CONSUMO'));
        DB::table('partidas')->insert(array('codigo'=>'342000','nompartida'=>'PRODUCTOS QUIMICOS Y FARMACEUTICOS'));
        DB::table('partidas')->insert(array('codigo'=>'343000','nompartida'=>'LLANTAS Y NEUMATICOS'));
        DB::table('partidas')->insert(array('codigo'=>'344000','nompartida'=>'PRODUCTOS DE CUERO Y CAUCHO'));
        DB::table('partidas')->insert(array('codigo'=>'345000','nompartida'=>'PRODUCTOS DE MINERALES NO METALICOS Y PLASTICOS'));
        DB::table('partidas')->insert(array('codigo'=>'346000','nompartida'=>'PRODUCTOS METALICOS'));
        DB::table('partidas')->insert(array('codigo'=>'391000','nompartida'=>'MATERIAL DE LIMPIEZA'));
        DB::table('partidas')->insert(array('codigo'=>'394000','nompartida'=>'INSTRUMENTAL MENOR MEDICO - QUIRURGICO'));
        DB::table('partidas')->insert(array('codigo'=>'395000','nompartida'=>'UTILES DE ESCRITORIO Y OFICINA'));
        DB::table('partidas')->insert(array('codigo'=>'397000','nompartida'=>'UTILES Y MATERIALES ELECTRICOS'));
        DB::table('partidas')->insert(array('codigo'=>'398000','nompartida'=>'OTROS REPUESTOS Y ACCESORIOS'));
        DB::table('partidas')->insert(array('codigo'=>'321000','nompartida'=>'PAPEL DE ESCRITORIO'));
        DB::table('partidas')->insert(array('codigo'=>'99999','nompartida'=>'SIN GRUPO ASIGNADO'));
        DB::table('partidas')->insert(array('codigo'=>'331000','nompartida'=>'HILADOS Y TELAS'));
        DB::table('partidas')->insert(array('codigo'=>'393000','nompartida'=>'UTENSILIOS DE COCINA Y COMEDOR'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidas');
    }
};
