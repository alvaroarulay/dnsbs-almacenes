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
        DB::table('unidades')->insert(array('id'=>1,'nomunidad'=>'AMARRO','sigla'=>'AMR','id_partida'=>10));
        DB::table('unidades')->insert(array('id'=>2,'nomunidad'=>'AMPOLLA','sigla'=>'AM','id_partida'=>12));
        DB::table('unidades')->insert(array('id'=>3,'nomunidad'=>'ARROBA','sigla'=>'ARR','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>4,'nomunidad'=>'BARRA','sigla'=>'BAR','id_partida'=>10));
        DB::table('unidades')->insert(array('id'=>5,'nomunidad'=>'BIDON','sigla'=>'BID','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>6,'nomunidad'=>'BLOCK','sigla'=>'BLK','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>7,'nomunidad'=>'BOLSA','sigla'=>'BL','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>8,'nomunidad'=>'BOLSITA','sigla'=>'BOL','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>9,'nomunidad'=>'BOTELLA','sigla'=>'BOT','id_partida'=>11));
        DB::table('unidades')->insert(array('id'=>10,'nomunidad'=>'CAJA','sigla'=>'BX','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>11,'nomunidad'=>'CARGA','sigla'=>'CRG','id_partida'=>9));
        DB::table('unidades')->insert(array('id'=>12,'nomunidad'=>'CILINDRO','sigla'=>'CY','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>13,'nomunidad'=>'CUARTILLA','sigla'=>'CRT','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>14,'nomunidad'=>'DOCENA','sigla'=>'DZN','id_partida'=>14));
        DB::table('unidades')->insert(array('id'=>15,'nomunidad'=>'FRACOS','sigla'=>'FRA','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>16,'nomunidad'=>'FRASCO','sigla'=>'FR','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>17,'nomunidad'=>'GALON','sigla'=>'GAL','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>18,'nomunidad'=>'GARRAFA','sigla'=>'GAR','id_partida'=>5));
        DB::table('unidades')->insert(array('id'=>19,'nomunidad'=>'HOJA','sigla'=>'HOJ','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>20,'nomunidad'=>'HOJAS','sigla'=>'HOJ','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>21,'nomunidad'=>'JUEGO','sigla'=>'SET','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>22,'nomunidad'=>'JUEGOS','sigla'=>'SET','id_partida'=>12));
        DB::table('unidades')->insert(array('id'=>23,'nomunidad'=>'KILO','sigla'=>'KIL','id_partida'=>2));
        DB::table('unidades')->insert(array('id'=>24,'nomunidad'=>'KIT','sigla'=>'KT','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>25,'nomunidad'=>'LITRO','sigla'=>'LTR','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>26,'nomunidad'=>'METRO','sigla'=>'MTR','id_partida'=>18));
        DB::table('unidades')->insert(array('id'=>27,'nomunidad'=>'MONTON','sigla'=>'MON','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>28,'nomunidad'=>'P.H.','sigla'=>'PH','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>29,'nomunidad'=>'PAQUETE','sigla'=>'PK','id_partida'=>4));
        DB::table('unidades')->insert(array('id'=>30,'nomunidad'=>'PAR','sigla'=>'PR','id_partida'=>11));
        DB::table('unidades')->insert(array('id'=>31,'nomunidad'=>'PIEZA','sigla'=>'PZA','id_partida'=>3));
        DB::table('unidades')->insert(array('id'=>32,'nomunidad'=>'PIEZAS','sigla'=>'PZA','id_partida'=>10));
        DB::table('unidades')->insert(array('id'=>33,'nomunidad'=>'PLIEGO','sigla'=>'ST','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>34,'nomunidad'=>'QUINTAL','sigla'=>'QQ','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>35,'nomunidad'=>'ROLLO','sigla'=>'RLL','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>36,'nomunidad'=>'SACHET','sigla'=>'SA','id_partida'=>11));
        DB::table('unidades')->insert(array('id'=>37,'nomunidad'=>'SET','sigla'=>'SET','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>38,'nomunidad'=>'UNIDAD','sigla'=>'UNI','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>39,'nomunidad'=>'UNIDADES','sigla'=>'UNI','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>40,'nomunidad'=>'VARIOS','sigla'=>'VAR','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>45,'nomunidad'=>'AMPOLLAS','sigla'=>'AMP','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>46,'nomunidad'=>'BLOCKS','sigla'=>'BLKS','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>47,'nomunidad'=>'BOLSAS','sigla'=>'BOL','id_partida'=>11));
        DB::table('unidades')->insert(array('id'=>48,'nomunidad'=>'CAJAS','sigla'=>'CJA','id_partida'=>11));
        DB::table('unidades')->insert(array('id'=>49,'nomunidad'=>'CAJITA','sigla'=>'CJI','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>50,'nomunidad'=>'CAPSULA','sigla'=>'CAP','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>51,'nomunidad'=>'CARRETA','sigla'=>'CARR','id_partida'=>18));
        DB::table('unidades')->insert(array('id'=>52,'nomunidad'=>'COMPRIMIDO','sigla'=>'COMP','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>53,'nomunidad'=>'COMPRIMIDOS','sigla'=>'COPS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>54,'nomunidad'=>'CONO','sigla'=>'CONO','id_partida'=>18));
        DB::table('unidades')->insert(array('id'=>55,'nomunidad'=>'EJEMPLAR','sigla'=>'EMP','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>56,'nomunidad'=>'GRAMOS','sigla'=>'GRS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>57,'nomunidad'=>'JERINGA','sigla'=>'JER','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>58,'nomunidad'=>'JERINGAS','sigla'=>'JERS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>59,'nomunidad'=>'KITS','sigla'=>'KITS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>60,'nomunidad'=>'LAMINA','sigla'=>'LMA','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>61,'nomunidad'=>'LATA','sigla'=>'LAT','id_partida'=>2));
        DB::table('unidades')->insert(array('id'=>62,'nomunidad'=>'LIBRA','sigla'=>'LBA','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>63,'nomunidad'=>'LIBRAS','sigla'=>'LIBS','id_partida'=>17));
        DB::table('unidades')->insert(array('id'=>64,'nomunidad'=>'METRO CUADRADO','sigla'=>'MT2','id_partida'=>18));
        DB::table('unidades')->insert(array('id'=>65,'nomunidad'=>'METROS','sigla'=>'MTS','id_partida'=>18));
        DB::table('unidades')->insert(array('id'=>66,'nomunidad'=>'METRO LINEAL','sigla'=>'MTL','id_partida'=>14));
        DB::table('unidades')->insert(array('id'=>67,'nomunidad'=>'PACK','sigla'=>'PACK','id_partida'=>14));
        DB::table('unidades')->insert(array('id'=>68,'nomunidad'=>'PAQUETES','sigla'=>'PQTS','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>69,'nomunidad'=>'PARES','sigla'=>'PARS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>70,'nomunidad'=>'PASTILLA','sigla'=>'PAST','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>71,'nomunidad'=>'POMO','sigla'=>'POM','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>72,'nomunidad'=>'ROLLOS','sigla'=>'ROL','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>73,'nomunidad'=>'SOBRE','sigla'=>'SBR','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>74,'nomunidad'=>'SOBRES','sigla'=>'SBRS','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>75,'nomunidad'=>'TABLETA','sigla'=>'TBL','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>76,'nomunidad'=>'TABLETAS','sigla'=>'TBLS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>77,'nomunidad'=>'TACO','sigla'=>'TAC','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>78,'nomunidad'=>'TACOS','sigla'=>'TCS','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>79,'nomunidad'=>'TALONARIO','sigla'=>'TAL','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>80,'nomunidad'=>'TARJETA','sigla'=>'TARJ','id_partida'=>1));
        DB::table('unidades')->insert(array('id'=>81,'nomunidad'=>'TEST','sigla'=>'TST','id_partida'=>12));
        DB::table('unidades')->insert(array('id'=>82,'nomunidad'=>'TIRAS','sigla'=>'TIR','id_partida'=>14));
        DB::table('unidades')->insert(array('id'=>83,'nomunidad'=>'TUBO','sigla'=>'TB','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>84,'nomunidad'=>'TUBOS','sigla'=>'TBS','id_partida'=>6));
        DB::table('unidades')->insert(array('id'=>85,'nomunidad'=>'VASO','sigla'=>'VS','id_partida'=>6));

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
