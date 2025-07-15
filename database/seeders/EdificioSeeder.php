<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EdificioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $edificios = [
            
            ['nomedif' => 'LITORAL','id_ciudad'=>1],
            ['nomedif' => 'AMBIENTES CALLE BUENO','id_ciudad'=>1],
            ['nomedif' => 'EDIFICIO CENTRAL','id_ciudad'=>1],
            ['nomedif' => 'EX FÁBRICA DE ALUMINIO (ALUBOL) ','id_ciudad'=>2],
            ['nomedif' => 'AMBIENTES WARA WARA','id_ciudad'=>2],
            ['nomedif' => 'EX OFICINA NACIONAL DE ASISTENCIA ALIMENTARIA (OFINAAL)','id_ciudad'=>2],
            ['nomedif' => 'REGIONAL SANTA CRUZ','id_ciudad'=>3],
            ['nomedif' => 'EL FUERTE','id_ciudad'=>3],
            ['nomedif' => 'REGIONAL COCHABAMBA 1','id_ciudad'=>4],
            ['nomedif' => 'REGIONAL COCHABAMBA 2','id_ciudad'=>4],
            ['nomedif' => 'REGIONAL CHUQUISACA','id_ciudad'=>5],
            ['nomedif' => 'REGIONAL BENI','id_ciudad'=>6],
            ['nomedif' => 'REGIONAL ORURO','id_ciudad'=>7],
        ];
        
        foreach ($edificios as $edificio) {
            DB::table('edificio')->insert([
                'nomedif' => $edificio['nomedif'],
                'id_ciudad' => $edificio['id_ciudad'],
            ]);
        }
    }
}
