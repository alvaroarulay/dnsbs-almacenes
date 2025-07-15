<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ciudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ciudades = [
            ['nomciudad'=>'LA PAZ'],
            ['nomciudad'=>'EL ALTO'],
            ['nomciudad'=>'SANTA CRUZ'],
            ['nomciudad'=>'COCHABAMBA'],
            ['nomciudad'=>'CHUQUISACA'],
            ['nomciudad'=>'BENI'],
            ['nomciudad'=>'ORURO'],
        ];
        
        foreach ($ciudades as $ciudad) {
            DB::table('ciudad')->insert([
                'nomciudad' => $ciudad['nomciudad'],
            ]);
        }
    }
}
