<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class direccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $direcciones = [
            ['nomdireccion' => 'DIRECCIÓN GENERAL EJECUTIVA','id_edif'=>3],
            ['nomdireccion' => 'DIRECCIÓN DE REGISTRO Y PROMOCIÓN','id_edif'=>3],
            ['nomdireccion' => 'DIRECCIÓN JURÍDICA','id_edif'=>3],
            ['nomdireccion' => 'DIRECCIÓN ADMINISTRATIVA Y FINANCIERA','id_edif'=>3],
            ['nomdireccion' => 'DISTRITAL SANTA CRUZ','id_edif'=>7],
            ['nomdireccion' => 'DISTRITAL COCHABAMBA','id_edif'=>9],
            ['nomdireccion' => 'DISTRITAL CHUQUISACA','id_edif'=>11],
            ['nomdireccion' => 'DISTRITAL ORURO','id_edif'=>13],
            ['nomdireccion' => 'DISTRITAL BENI','id_edif'=>12],
            ['nomdireccion' => 'DIRECCIÓN DE LIQUIDACIÓN DE ENTES GESTORES DE LA SEGURIDAD SOCIAL','id_edif'=>1],
            ['nomdireccion' => 'DIRECCIÓN DE DISPOSICIÓN DE BIENES Y RECUPERACIÓN DE ACTIVOS EXIGIBLES','id_edif'=>2],

        ];
        
        foreach ($direcciones as $direccion) {
            DB::table('direccion')->insert([
                'nomdireccion' => $direccion['nomdireccion'],
                'id_edif' => $direccion['id_edif'],
            ]);
        }
    }
}
