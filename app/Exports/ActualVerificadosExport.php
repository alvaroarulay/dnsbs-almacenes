<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ActualVerificadosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $datos = DB::table('actual')->join('auxiliar','actual.ID_AUX','=','auxiliar.id')
                                ->join('codcont','auxiliar.id_codcont','=','codcont.id')
                                ->join('estado','actual.ESTADO','=','estado.id')
                                ->join('resp','actual.ID_RESPONSABLE','=','resp.id')
                                ->join('oficinav','resp.ID_OFIC','=','oficinav.id')
                                ->join('direccion','oficinav.id_direccion','=','direccion.id')
                                ->join('edificio','direccion.id_edif','=','edificio.id')
                                ->join('ciudad','edificio.id_ciudad','=','ciudad.id')
                                ->join('ap','ap.CODIGO','=','actual.CODIGO_ANTERIOR')
                                ->select('actual.COD_COMPLETO',
                                        'actual.CODIGO_ARPRO',
                                        'codcont.nombre AS GRUPO_CONTABLE',
                                        'auxiliar.nomaux AS AUXILIAR',
                                        'actual.DESCRIPCION_ARPRO',
                                        'actual.COMPLEMENTOS','actual.MARCA','actual.MODELO','actual.SERIE','actual.PROCESADOR','actual.GENERACION',
                                        'actual.HDD','actual.RAM','actual.MATERIAL','actual.COLOR','actual.MEDIDAS','actual.OBSERV',
                                        'estado.nomestado AS ESTADO',
                                        'ciudad.nomciudad','edificio.nomedif','direccion.nomdireccion','oficinav.nomofic','oficinav.ubi_espec',
                                        'resp.NOMBRE AS RESPONSABLE','resp.CARGO','resp.CI')
                       ->distinct()                
                       ->where('auxiliar.condicion','=',1)
                       ->where('actual.CODIGO_ANTERIOR','!=','-/-')
                       ->orderBy('actual.CODIGO_ARPRO', 'ASC')
                       ->get();
        $filtrados = $datos->map(function($item) {

            return [
                'codigo' => $item->COD_COMPUESTO,
                'codanterior' => $item->COD_COMPLETO,
                'otroscodigos' => $item->CODIGO_ANTERIOR,
                'auxiliar' => $item->nomaux, 
                'descripcion' => $item->DESCRIPCION, 
                'estado' => $item->ESTADO, 
                'observ' => $item->OBSERV, 
                'ubicacion' => 'EDIF: '. $item->EDIFICIO.' PISO : '. $item->PISO . ' OFICINA: '.$item->OFICINA .' '. $item->UBI_ESPEC, 
            ];
        });
    }
}
