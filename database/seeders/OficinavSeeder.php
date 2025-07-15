<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Illuminate\Support\Facades\DB;

class OficinavSeeder extends SpreadsheetSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // Asegúrate de que el archivo exista en la ruta correcta
            $this->file = '/database/seeders/BDOFICINAS.xlsx'; // Ruta relativa
            $this->tablename = 'oficinav';
            $this->timestamps = true;
            parent::run();

        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}
