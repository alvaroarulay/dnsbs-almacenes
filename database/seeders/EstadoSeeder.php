<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['sigla' => 'E','nomestado'=>'EXCELENTE','porcentaje'=>1],
            ['sigla' => 'B','nomestado'=>'BUENO','porcentaje'=>0.7],
            ['sigla' => 'R+','nomestado'=>'REGULAR','porcentaje'=>0.6],
            ['sigla' => 'R','nomestado'=>'REGULAR','porcentaje'=>0.5],
            ['sigla' => 'R-','nomestado'=>'REGULAR','porcentaje'=>0.4],
            ['sigla' => 'M','nomestado'=>'MALO','porcentaje'=>0.2],
            ['sigla' => 'PB','nomestado'=>'PARA BAJA','porcentaje'=>0],

        ];
        
        foreach ($estados as $estado) {
            DB::table('estado')->insert([
                'sigla' => $estado['sigla'],
                'nomestado' => $estado['nomestado'],
                'porcentaje' => $estado['porcentaje'],
            ]);
        }
    }
}
