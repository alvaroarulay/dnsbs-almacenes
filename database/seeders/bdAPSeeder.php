<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class bdAPSeeder extends SpreadsheetSeeder
{    
    public function run()
    {
        try {
            // Asegúrate de que el archivo exista en la ruta correcta
            $this->file = '/database/seeders/BDAP.xlsx'; // Ruta relativa
            $this->tablename = 'AP';
            $this->timestamps = false;

            parent::run();
        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}