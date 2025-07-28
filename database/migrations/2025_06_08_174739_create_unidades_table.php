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
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nomunidad')->nullable();
            $table->string('sigla')->nullable();
            $table->foreignId('id_partida')->constrained('partidas');
            $table->timestamps();
        });
        DB::table('unidades')->insert(array('nomunidad'=>'AMARRO','sigla'=>'AMR','id_partida'=>10));
        DB::table('unidades')->insert(array('nomunidad'=>'AMPOLLA','sigla'=>'AM','id_partida'=>12));
        DB::table('unidades')->insert(array('nomunidad'=>'ARROBA','sigla'=>'ARR','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'BARRA','sigla'=>'BAR','id_partida'=>10));
        DB::table('unidades')->insert(array('nomunidad'=>'BIDON','sigla'=>'BID','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'BLOCK','sigla'=>'BLK','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'BOLSA','sigla'=>'BL','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'BOLSITA','sigla'=>'BOL','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'BOTELLA','sigla'=>'BOT','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'CAJA','sigla'=>'BX','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'CARGA','sigla'=>'CRG','id_partida'=>9));
        DB::table('unidades')->insert(array('nomunidad'=>'CILINDRO','sigla'=>'CY','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'CUARTILLA','sigla'=>'CRT','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'DOCENA','sigla'=>'DZN','id_partida'=>14));
        DB::table('unidades')->insert(array('nomunidad'=>'FRACOS','sigla'=>'FRA','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'FRASCO','sigla'=>'FR','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'GALON','sigla'=>'GAL','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'GARRAFA','sigla'=>'GAR','id_partida'=>5));
        DB::table('unidades')->insert(array('nomunidad'=>'HOJA','sigla'=>'HOJ','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'HOJAS','sigla'=>'HOJ','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'JUEGO','sigla'=>'SET','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'JUEGOS','sigla'=>'SET','id_partida'=>12));
        DB::table('unidades')->insert(array('nomunidad'=>'KILO','sigla'=>'KIL','id_partida'=>2));
        DB::table('unidades')->insert(array('nomunidad'=>'KIT','sigla'=>'KT','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'LITRO','sigla'=>'LTR','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'METRO','sigla'=>'MTR','id_partida'=>18));
        DB::table('unidades')->insert(array('nomunidad'=>'MONTON','sigla'=>'MON','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'P.H.','sigla'=>'PH','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'PAQUETE','sigla'=>'PK','id_partida'=>4));
        DB::table('unidades')->insert(array('nomunidad'=>'PAR','sigla'=>'PR','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'PIEZA','sigla'=>'PZA','id_partida'=>3));
        DB::table('unidades')->insert(array('nomunidad'=>'PIEZAS','sigla'=>'PZA','id_partida'=>10));
        DB::table('unidades')->insert(array('nomunidad'=>'PLIEGO','sigla'=>'ST','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'QUINTAL','sigla'=>'QQ','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'ROLLO','sigla'=>'RLL','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'SACHET','sigla'=>'SA','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'SET','sigla'=>'SET','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'UNIDAD','sigla'=>'UNI','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'UNIDADES','sigla'=>'UNI','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'VARIOS','sigla'=>'VAR','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'PACK','sigla'=>'PAK','id_partida'=>15));
        DB::table('unidades')->insert(array('nomunidad'=>'UNIDAD','sigla'=>'UNI','id_partida'=>15));
        DB::table('unidades')->insert(array('nomunidad'=>'SET','sigla'=>'SET','id_partida'=>15));
        DB::table('unidades')->insert(array('nomunidad'=>'VARIOS','sigla'=>'VAR','id_partida'=>15));
        DB::table('unidades')->insert(array('nomunidad'=>'AMPOLLAS','sigla'=>'AMP','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'BLOCKS','sigla'=>'BLKS','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'BOLSAS','sigla'=>'BOL','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'CAJAS','sigla'=>'CJA','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'CAJITA','sigla'=>'CJI','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'CAPSULA','sigla'=>'CAP','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'CARRETA','sigla'=>'CARR','id_partida'=>18));
        DB::table('unidades')->insert(array('nomunidad'=>'COMPRIMIDO','sigla'=>'COMP','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'COMPRIMIDOS','sigla'=>'COPS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'CONO','sigla'=>'CONO','id_partida'=>18));
        DB::table('unidades')->insert(array('nomunidad'=>'EJEMPLAR','sigla'=>'EMP','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'GRAMOS','sigla'=>'GRS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'JERINGA','sigla'=>'JER','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'JERINGAS','sigla'=>'JERS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'KITS','sigla'=>'KITS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'LAMINA','sigla'=>'LMA','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'LATA','sigla'=>'LAT','id_partida'=>2));
        DB::table('unidades')->insert(array('nomunidad'=>'LIBRA','sigla'=>'LBA','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'LIBRAS','sigla'=>'LIBS','id_partida'=>17));
        DB::table('unidades')->insert(array('nomunidad'=>'METRO CUADRADO','sigla'=>'MT2','id_partida'=>18));
        DB::table('unidades')->insert(array('nomunidad'=>'METROS','sigla'=>'MTS','id_partida'=>18));
        DB::table('unidades')->insert(array('nomunidad'=>'METRO LINEAL','sigla'=>'MTL','id_partida'=>14));
        DB::table('unidades')->insert(array('nomunidad'=>'PACK','sigla'=>'PACK','id_partida'=>14));
        DB::table('unidades')->insert(array('nomunidad'=>'PAQUETES','sigla'=>'PQTS','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'PARES','sigla'=>'PARS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'PASTILLA','sigla'=>'PAST','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'POMO','sigla'=>'POM','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'ROLLOS','sigla'=>'ROL','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'SOBRE','sigla'=>'SBR','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'SOBRES','sigla'=>'SBRS','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'TABLETA','sigla'=>'TBL','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'TABLETAS','sigla'=>'TBLS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'TACO','sigla'=>'TAC','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'TACOS','sigla'=>'TCS','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'TALONARIO','sigla'=>'TAL','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'TARJETA','sigla'=>'TARJ','id_partida'=>1));
        DB::table('unidades')->insert(array('nomunidad'=>'TEST','sigla'=>'TST','id_partida'=>12));
        DB::table('unidades')->insert(array('nomunidad'=>'TIRAS','sigla'=>'TIR','id_partida'=>14));
        DB::table('unidades')->insert(array('nomunidad'=>'TUBO','sigla'=>'TB','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'TUBOS','sigla'=>'TBS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'VASO','sigla'=>'VS','id_partida'=>6));
        DB::table('unidades')->insert(array('nomunidad'=>'UNIDAD','sigla'=>'UNID','id_partida'=>11));
        DB::table('unidades')->insert(array('nomunidad'=>'PACK','sigla'=>'PACK','id_partida'=>15));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidades');
    }
};
