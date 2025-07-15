<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class ActualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resp = DB::table('resp')->select('id','unidad','codresp','codofic')->get();
        $table = new TableReader(storage_path('app/vsiaf/dbfs/ACTUAL.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            $id_resp =DB::table('resp'
            )->select('id')
            ->where('codofic','=',$record->get('codofic'))
            ->where('codresp','=',$record->get('codresp'))
            ->where('unidad','=',$record->get('unidad'))
            ->first();
            if($id_resp==null){
                $id_resp_valor=201;
            }else{
                $id_resp_valor = $id_resp->id;
            }
            $data = $this->extraerAtributos($record->get('descrip'));
            try {
                DB::table('ap')->insert([
                    'unidad' => $record->get('unidad'), 
                    'CODIGO' => $record->get('codigo'),
                    'ID_GC' => $record->get('codcont'),
                    'MARCA' => $data['marca']  ?? null,
                    'MODELO' => $data['modelo']  ?? null,
                    'SERIE' => $data['serie']  ?? null,
                    'PROCESADOR' => $data['procesador']  ?? null,
                    'HDD' => $data['disco']  ?? null,
                    'RAM' => $data['ram']  ?? null,
                    'COLOR' => $data['color']  ?? null,
                    'MEDIDAS' => $data['medidas']  ?? null,
                    'DESCRIPCION' => $record->get('descrip')  ?? null,
                    'ESTADO' => $record->get('codestado')  ?? null,
                    'ID_RESP' => $id_resp_valor,
                    'FECHA_INC' => $record->get('ano') . '-' . $record->get('mes') . '-' . $record->get('dia')  ?? null, 
                    'COSTO_HIS' => $record->get('costo')  ?? null,
                    'DEP_GEST' => $record->get('depacu')  ?? null, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                dump($record->get('codigo'));
                //dump($id_resp->id);
                dd($e->getMessage());
            }
            
        }
    }
    /**
 * Extrae atributos de una descripción de producto.
 *
 * @param string $descripcion La descripción completa del producto.
 * @return array Retorna un arreglo asociativo con claves: 
 *               'descripcion', 'marca', 'modelo', 'serie', 'color', 'medidas'
 */
function extraerAtributos($descripcion)
{
    $atributos = [
        'marca' => null,
        'modelo' => null,
        'serie' => null,
        'procesador' => null,
        'disco' => null,
        'ram' => null,
        'color' => null,
        'medidas' => null
    ];

    // Normalizamos el texto
    $texto = strtoupper(trim(preg_replace('/\s+/', ' ', $descripcion)));

    // Detectamos RAM y HDD al principio, incluso con alias
    if (preg_match('/(?:^|\s)(\d+)\s*(?:RAM|MR)/', $texto, $matchRam)) {
        $atributos['ram'] = $matchRam[1];
    }
    if (preg_match('/(?:^|\s)(\d+)\s*(?:HDD|DISCO|DD)/', $texto, $matchHdd)) {
        $atributos['disco'] = $matchHdd[1];
    }

    // Diccionario de claves y sus alias
    $claves = [
        'marca' => ['MARCA'],
        'modelo' => ['MODELO', 'MOD.','Mod.','MOD'],
        'serie' => ['S/N', 'SERIE','S/N:', 'SERIE:','S/N.', 'SERIE.','SERVICE TAG','S/N ', 'SERIE ','S/N: ', 'SERIE: ','S/N. ', 'SERIE. ','SERVICE TAG '],
        'procesador' => ['PROCESADOR', 'PROC.'],
        'disco' => ['HDD', 'DISCO', 'DD','D.DURO','DISCO DURO'],
        'ram' => ['RAM', 'MR'],
        'color' => ['COLOR','C/'],
        'medidas' => ['MEDIDAS'],
    ];

    foreach ($claves as $campo => $aliasList) {
        foreach ($aliasList as $alias) {
            if ($campo === 'color' && $alias === 'C/') {
                // Buscar segunda coincidencia de 'C/'
                $primero = stripos($texto, 'C/');
                $pos = ($primero !== false) ? stripos($texto, 'C/', $primero + 2) : false;
            } else {
                $pos = stripos($texto, $alias);
            }
            if ($pos !== false) {
                $inicio = $pos + strlen($alias);
                $fin = strlen($texto);

                // Buscar la siguiente clave en cualquier alias
                foreach ($claves as $siguienteCampo => $siguientesAlias) {
                    if ($siguienteCampo === $campo) continue;
                    foreach ($siguientesAlias as $sigAlias) {
                        $posFin = strpos($texto, $sigAlias, $inicio);
                        if ($posFin !== false && $posFin < $fin) {
                            $fin = $posFin;
                        }
                    }
                }

                $valor = trim(substr($texto, $inicio, $fin - $inicio));

                // Si es la serie, cortar hasta la coma si existe
                if ($campo === 'serie') {
                    $partes = preg_split('/[\s,]/', $valor);
                    $valor = isset($partes[1]) ? $partes[0] . ' ' . $partes[1] : $partes[0];
                }
                if ($campo === 'marca') {
                    $valor = explode(',', $valor)[0]; // Solo el contenido antes de la coma
                }
                if ($campo === 'modelo') {
                    $valor = explode(',', $valor)[0]; // Solo el contenido antes de la coma
                }
                if ($campo === 'color') {
                    $partes = preg_split('/[\s,]/', $valor);
                    $valor = isset($partes[1]) ? $partes[0] . ' ' . $partes[1] : $partes[0];
                }
                if (!$atributos[$campo]) {
                    $atributos[$campo] = $valor;
                }
            }
        }
    }

    return $atributos;
}




}
