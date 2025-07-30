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
   
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN CENTTRAL DNSBS','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'Clínica Dental Cnl Roberto Quintanilla','ubi_espec'=>'','id_establecimiento'=>2));
        DB::table('almacen')->insert(array('nomalmacen'=>'HOSPITAL POLICIAL VIRGEN DE COPACABANA 1','ubi_espec'=>'','id_establecimiento'=>9));
        DB::table('almacen')->insert(array('nomalmacen'=>'HOSPITAL POLICIAL VIRGEN DE COPACABANA 1','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN SANTA CRUZ','ubi_espec'=>'','id_establecimiento'=>8));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN BENI','ubi_espec'=>'','id_establecimiento'=>7));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN CHUQUISACA','ubi_espec'=>'','id_establecimiento'=>11));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN EL ALTO','ubi_espec'=>'','id_establecimiento'=>3));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN ORURO','ubi_espec'=>'','id_establecimiento'=>4));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN PANDO','ubi_espec'=>'','id_establecimiento'=>6));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN POTOSÍ','ubi_espec'=>'','id_establecimiento'=>5));
        DB::table('almacen')->insert(array('nomalmacen'=>'ALMACEN TARIJA','ubi_espec'=>'','id_establecimiento'=>10));

        DB::table('almacen')->insert(array('nomalmacen'=>'SECRETARIA GENERAL','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'ARCHIVO CENTRAL','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'ASESORIA JURIDICA','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'RELACIONES PUBLICAS','ubi_espec'=>'','id_establecimiento'=>1));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA PERSONAL','ubi_espec'=>'','id_establecimiento'=>12));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA PP. OO.','ubi_espec'=>'','id_establecimiento'=>13));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA DPTO. DE SALUD','ubi_espec'=>'','id_establecimiento'=>14));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA BIENESTAR SOCIAL','ubi_espec'=>'','id_establecimiento'=>15));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA PSICOLOGIA','ubi_espec'=>'','id_establecimiento'=>16));
        DB::table('almacen')->insert(array('nomalmacen'=>'OFICINA DE SISTEMAS','ubi_espec'=>'','id_establecimiento'=>17));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECRETARIA ADMINISTRATIVA','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'DIV. CONTABILIDAD','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'DIV. TESORERIA','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'AUX. TESORERIA','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN EGRESOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN CREDITOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'CAJA CHICA','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN INGRESOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN PASAJES Y VIATICOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'BRIGADAS MOVILES','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'DIV. PRESUPUESTOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'AUX. ADMINISTRATIVA I','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'AUX. ADMINISTRATIVA II','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'DIV. ADQUISICIONES','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN ALMACEN CENTRAL','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SECCIÓN ACTIVOS FIJOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'DIV. IMPUESTOS','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'SEGURIDAD','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'COCINA','ubi_espec'=>'','id_establecimiento'=>18));
        DB::table('almacen')->insert(array('nomalmacen'=>'LIMPIEZA','ubi_espec'=>'','id_establecimiento'=>18));
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
