<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;


class ConsolidadoAoeSeeder extends Seeder
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
                /*$this->file = '/database/seeders/CONSOLIDADOAOETOTAL.xlsx'; // Ruta relativa
            $this->tablename = 'actualaoe';
            $this->timestamps = false;
            //DB::table('actualaoe')->insertOrIgnore($data->toArray());
            parent::run();
            $data = DB::table('actualaoe')->get();

            // Inserta sin sobrescribir
            DB::table('actualaoe')->insertOrIgnore($data->toArray());
         
            $data = Excel::toCollection(new SpreadsheetSeeder, $this->file)->first(); // Solo la primera hoja

            // Definir el tamaño del lote
            $batchSize = 500;
            $chunks = $data->chunk($batchSize);

            // Procesar cada lote por separado
            foreach ($chunks as $chunk) {
                // Convierte cada fila a un arreglo asociativo
                $chunkArray = [];
                foreach ($chunk as $row) {
                    $chunkArray[] = [
                        'COD_ANT' => $row['COD_ANT'], // Ajusta las claves según las columnas
                        'COD_COMPLETO' => $row['COD_COMPLETO'],
                        'OTROS_COD' => $row['OTROS_COD'],
                        'CODIGO' => $row['CODIGO'],
                        'COD_COMPUESTO' => $row['COD_COMPUESTO'],
                        'ID_GC' => $row['ID_GC'],
                        'ID_AUX' => $row['ID_AUX'],
                        'DESCRIP_ARPRO' => $row['DESCRIP_ARPRO'],
                        'COMPLEMENTOS' => $row['COMPLEMENTOS'],
                        'MARCA' => $row['MARCA'],
                        'MODELO' => $row['MODELO'],
                        'SERIE' => $row['SERIE'],
                        'PROCESADOR' => $row['PROCESADOR'],
                        'GENERACION' => $row['GENERACION'],
                        'HDD' => $row['HDD'],
                        'RAM' => $row['RAM'],
                        'MATERIAL' => $row['MATERIAL'],
                        'COLOR' => $row['COLOR'],
                        'MEDIDAS' => $row['MEDIDAS'],
                        'CUSTODIO' => $row['CUSTODIO'],
                        'OBSERV' => $row['OBSERV'],
                        'DESCRIPCION' => $row['DESCRIPCION'],
                        'ESTADO' => $row['ESTADO'],
                        'DEPARTAMENTO' => $row['DEPARTAMENTO'],
                        'EDIFICIO' => $row['EDIFICIO'],
                        'PISO' => $row['PISO'],
                        'UBI_ESPEC' => $row['UBI_ESPEC'],
                        'OFICINA' => $row['OFICINA'],
                        'ID_RESP' => $row['ID_RESP'],
                        'INVENTARIADOR' => $row['INVENTARIADOR'],
                        'FECHA' => $row['FECHA'],
                    ];
                }

                // Inserta el lote en la tabla actualaoe
                DB::table('actualaoe')->insert($chunkArray);
            }
            $batchSize = 500; // Procesar en lotes más pequeños
            $chunks = $data->chunk($batchSize);
            
            // Procesar los registros en lotes y construir consultas SQL manualmente
            foreach ($chunks as $chunk) {
                $sql = "INSERT INTO actualaoe (COD_ANT) VALUES "; // Ajustar los nombres de las columnas
                $values = [];
            
                foreach ($chunk as $row) {
                    $values[] = "('" . $row['COD_ANT'] . "')"; // Ajusta según las columnas que estés usando
                }
            
                // Combina los valores en una sola cadena
                $sql .= implode(", ", $values);
            
                // Ejecuta la consulta SQL en crudo
                DB::statement($sql);
            }*/
            DB::table('actualaoe')->get()->each(function ($record) {
                $codigo = $record->COD_ANT;
                $verificado = 1;
                DB::table('aoe')->where('CODIGO','=', $codigo)->update(['VERIFICACION' => $verificado]);
            });

        } catch (\Exception $e) {
            $message = 'Failed to insert rows from file "' . $this->file . '": ' . $e->getMessage();
            echo $message;
            throw new \Exception($message);
        }
    }
}
