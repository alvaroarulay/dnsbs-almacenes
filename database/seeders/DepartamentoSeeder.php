<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unidades = [
            ['nombre' => 'La Paz'],
            ['nombre' => 'El Alto'],
            ['nombre' => 'Santa Cruz'],
            ['nombre' => 'Cochabamba'],
            ['nombre' => 'Oruro'],
            ['nombre' => 'Chuquisaca'],
            ['nombre' => 'Beni'],
        ];

        foreach ($unidades as $unidad) {
            DB::table('unidadadminv')->insert([
                'nombre' => $unidad['nombre'],
            ]);
        }
    }
}
