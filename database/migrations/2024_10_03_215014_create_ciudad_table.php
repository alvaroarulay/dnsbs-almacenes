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
        DB::table('ciudad')->insert(array('nomciudad'=>'LA PAZ','sigla'=>'LPZ'));
        DB::table('ciudad')->insert(array('nomciudad'=>'EL ALTO','sigla'=>'EAL'));
        DB::table('ciudad')->insert(array('nomciudad'=>'COCHABAMBA','sigla'=>'CBBA'));
        DB::table('ciudad')->insert(array('nomciudad'=>'SANTA CRUZ','sigla'=>'SCZ'));
        DB::table('ciudad')->insert(array('nomciudad'=>'BENI','sigla'=>'BEN'));
        DB::table('ciudad')->insert(array('nomciudad'=>'PANDO','sigla'=>'PND'));
        DB::table('ciudad')->insert(array('nomciudad'=>'ORURO','sigla'=>'ORU'));
        DB::table('ciudad')->insert(array('nomciudad'=>'POTOSI','sigla'=>'PTS'));
        DB::table('ciudad')->insert(array('nomciudad'=>'CHUQUISACA','sigla'=>'CHU'));
        DB::table('ciudad')->insert(array('nomciudad'=>'TARIJA','sigla'=>'TJA'));
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
