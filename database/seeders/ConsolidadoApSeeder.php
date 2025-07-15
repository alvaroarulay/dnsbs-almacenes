<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;

class ConsolidadoApSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            //Asegúrate de que el archivo exista en la ruta correcta
            /*$this->file = '/database/seeders/CONSOLIDADOAP.xlsx'; // Ruta relativa
            $this->tablename = 'actual';
            $this->timestamps = true;
            parent::run();*/
            DB::table('actual')->get()->each(function ($record) {
                $codigo = $record->CODIGO_ANTERIOR;
                $verificado = 1;
                DB::table('ap')->where('CODIGO','=', $codigo)->update(['VERIFICACION' => $verificado]);
            });

        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}
