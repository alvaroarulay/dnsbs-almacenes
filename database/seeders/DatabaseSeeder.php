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
        $this->call(ActualSeeder::class);
        //$this->call(oficinaSeeder::class);
        //$this->call(ResponsableSeeder::class);
        /*$this->call(bdAPSeeder::class);
        $this->call(bdaoeSeeder::class);
        $this->call(dbMenoresSeeder::class);
        $this->call(UnidadSeeder::class);

        $this->call(EstadoSeeder::class);
        $this->call(ciudadSeeder::class);
        $this->call(EdificioSeeder::class);
        $this->call(direccionSeeder::class);
        $this->call(oficinavSeeder::class);
       

        $this->call(codcontSeeder::class);
        $this->call(AuxiliaresSeeder::class);
              */
        //$this->call(ConsolidadoApSeeder::class);
        //$this->call(ConsolidadoMenSeeder::class);
        //$this->call(ConsolidadoAoeSeeder::class);
    }
}
