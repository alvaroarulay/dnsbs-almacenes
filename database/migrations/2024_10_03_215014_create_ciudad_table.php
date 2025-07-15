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
        Schema::create('ciudad', function (Blueprint $table) {
            $table->id();
            $table->string('nomciudad');
            $table->string('sigla')->nullable();
            $table->timestamps();
        });
        DB::table('ciudad')->insert(array('id'=>1,'nomciudad'=>'LA PAZ','sigla'=>'LPZ'));
        DB::table('ciudad')->insert(array('id'=>2,'nomciudad'=>'EL ALTO','sigla'=>'EAL'));
        DB::table('ciudad')->insert(array('id'=>3,'nomciudad'=>'COCHABAMBA','sigla'=>'CBBA'));
        DB::table('ciudad')->insert(array('id'=>4,'nomciudad'=>'SANTA CRUZ','sigla'=>'SCZ'));
        DB::table('ciudad')->insert(array('id'=>5,'nomciudad'=>'BENI','sigla'=>'BEN'));
        DB::table('ciudad')->insert(array('id'=>6,'nomciudad'=>'PANDO','sigla'=>'PND'));
        DB::table('ciudad')->insert(array('id'=>7,'nomciudad'=>'ORURO','sigla'=>'ORU'));
        DB::table('ciudad')->insert(array('id'=>8,'nomciudad'=>'POTOSI','sigla'=>'PTS'));
        DB::table('ciudad')->insert(array('id'=>9,'nomciudad'=>'CHUQUISACA','sigla'=>'CHU'));
        DB::table('ciudad')->insert(array('id'=>10,'nomciudad'=>'TARIJA','sigla'=>'TJA'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciudad');
    }
};
