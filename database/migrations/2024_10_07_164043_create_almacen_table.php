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
        Schema::create('almacen', function (Blueprint $table) {
            $table->id();
            $table->string('nomalmacen')->nullable();
            $table->string('ubi_espec')->nullable();
            $table->Integer('seleccionado')->nullable()->default(0);
            $table->foreignId('id_establecimiento')->constrained('establecimiento');
            $table->timestamps();
        });
   
        DB::table('almacen')->insert(array('id'=>1,'nomalmacen'=>'ALMACEN CENTTRAL DNSBS','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('id'=>2,'nomalmacen'=>'Clínica Dental Cnl Roberto Quintanilla','ubi_espec'=>'','id_establecimiento'=>2));
        DB::table('almacen')->insert(array('id'=>3,'nomalmacen'=>'HOSPITAL POLICIAL VIRGEN DE COPACABANA 1','ubi_espec'=>'','id_establecimiento'=>9));
        DB::table('almacen')->insert(array('id'=>4,'nomalmacen'=>'HOSPITAL POLICIAL VIRGEN DE COPACABANA 1','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('id'=>5,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>8));
        DB::table('almacen')->insert(array('id'=>6,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>7));
        DB::table('almacen')->insert(array('id'=>7,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>11));
        DB::table('almacen')->insert(array('id'=>8,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>3));
        DB::table('almacen')->insert(array('id'=>9,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>4));
        DB::table('almacen')->insert(array('id'=>10,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>6));
        DB::table('almacen')->insert(array('id'=>11,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>5));
        DB::table('almacen')->insert(array('id'=>12,'nomalmacen'=>'ALMACEN','ubi_espec'=>'','id_establecimiento'=>10));
      }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('almacen');
    }
};
