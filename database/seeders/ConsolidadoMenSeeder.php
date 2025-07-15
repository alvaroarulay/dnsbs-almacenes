<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;


class ConsolidadoMenSeeder extends Seeder
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
            /*$this->file = '/database/seeders/CONSOLIDADOMENORES.xlsx'; // Ruta relativa
            $this->tablename = 'actualmen';
            $this->timestamps = true;
            parent::run();*/

            DB::table('actualmen')->get()->each(function ($record) {
                $codigo = $record->COD_ANT;
                $verificado = 1;
                DB::table('menores')->where('CODIGO','=', $codigo)->update(['VERIFICACION' => $verificado]);
            });

        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}
