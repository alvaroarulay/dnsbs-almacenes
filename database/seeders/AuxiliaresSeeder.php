<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class AuxiliaresSeeder extends SpreadsheetSeeder
{
    public function run()
    {
        try {
            // Asegúrate de que el archivo exista en la ruta correcta
            $this->file = '/database/seeders/BDAUX.xlsx'; // Ruta relativa
            $this->tablename = 'auxiliar';
            $this->timestamps = false;
            parent::run();

        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}
