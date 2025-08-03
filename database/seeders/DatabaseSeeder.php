<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         /*$this->call(SalidasSeeder::class);
        $this->call(facturas2024Seeder::class);*/
        //$this->call(entradas2025Seeder::class);
        $this->call(facturas2025Seeder::class);
        //$this->call(Salidas2025Seeder::class);
        
    }
}
