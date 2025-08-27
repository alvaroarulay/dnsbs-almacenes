<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(entradas2024Seeder::class);
        $this->call(facturas2024Seeder::class);
        $this->call(SalidasSeeder::class);
        $this->call(entradas2025Seeder::class);
        $this->call(facturas2025Seeder::class);
        $this->call(Salidas2025Seeder::class);
        
    }
}
