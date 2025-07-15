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
        DB::table('partidas')->insert(array('id'=>'1','codigo'=>'256000','nompartida'=>'IMPRENTA'));
        DB::table('partidas')->insert(array('id'=>'2','codigo'=>'311000','nompartida'=>'CARNES Y SUS DERIVADOS'));
        DB::table('partidas')->insert(array('id'=>'3','codigo'=>'332000','nompartida'=>'CONFECCIONES TEXTILES'));
        DB::table('partidas')->insert(array('id'=>'4','codigo'=>'333000','nompartida'=>'PRENDAS DE VESTIR'));
        DB::table('partidas')->insert(array('id'=>'5','codigo'=>'341100','nompartida'=>'COMBUSTIBLES Y LUBRICANTES PARA CONSUMO'));
        DB::table('partidas')->insert(array('id'=>'6','codigo'=>'342000','nompartida'=>'PRODUCTOS QUIMICOS Y FARMACEUTICOS'));
        DB::table('partidas')->insert(array('id'=>'7','codigo'=>'343000','nompartida'=>'LLANTAS Y NEUMATICOS'));
        DB::table('partidas')->insert(array('id'=>'8','codigo'=>'344000','nompartida'=>'PRUDUCTOS DE CUERO Y CAUCHO'));
        DB::table('partidas')->insert(array('id'=>'9','codigo'=>'345000','nompartida'=>'PRODUCTOS DE MINERALES NO METALICOS Y PLASTICOS'));
        DB::table('partidas')->insert(array('id'=>'10','codigo'=>'346000','nompartida'=>'PRODUCTOS METALICOS'));
        DB::table('partidas')->insert(array('id'=>'11','codigo'=>'391000','nompartida'=>'MATERIAL DE LIMPIEZA'));
        DB::table('partidas')->insert(array('id'=>'12','codigo'=>'394000','nompartida'=>'INSTRUMENTAL MENOR METIDCO - QUIRURGICO'));
        DB::table('partidas')->insert(array('id'=>'13','codigo'=>'395000','nompartida'=>'UTILES DE ESCRITORIO Y OFICINA'));
        DB::table('partidas')->insert(array('id'=>'14','codigo'=>'397000','nompartida'=>'UTILES Y MATERIALES ELECTRICOS'));
        DB::table('partidas')->insert(array('id'=>'15','codigo'=>'398000','nompartida'=>'OTROS REPUESTOS Y ACCESORIOS'));
        DB::table('partidas')->insert(array('id'=>'16','codigo'=>'321000','nompartida'=>'PAPEL DE ESCRITORIO'));
        DB::table('partidas')->insert(array('id'=>'17','codigo'=>'99999','nompartida'=>'SIN GRUPO ASIGNADO'));
        DB::table('partidas')->insert(array('id'=>'18','codigo'=>'331000','nompartida'=>'HILADOS Y TELAS'));
        DB::table('partidas')->insert(array('id'=>'19','codigo'=>'393000','nompartida'=>'UTENSILIOS DE COCINA Y COMEDOR'));
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
