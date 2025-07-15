<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ApFaltantesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('ap')->join('codcont','ap.ID_GC','=','codcont.id')
                    ->join('resp','ap.ID_RESP','=','resp.id')
                    ->join('oficina','resp.ID_OFIC','=','oficina.id')
                    ->join('pisos','oficina.id_piso','=','pisos.id')
                    ->join('edificio','pisos.id_edif','=','edificio.id')
                    ->join('ciudad','edificio.id_ciudad','=','ciudad.id')
            ->select('ap.CODIGO','codcont.nombre','ap.DESCRIPCION','ap.OBSERV',
            'ciudad.nomciudad','edificio.nomedif','pisos.nompisos','oficina.nomofic',
            'resp.NOMBRE','resp.CARGO','resp.CI')
            ->where('ap.VERIFICACION','=',0)->get();
    }

    public function headings(): array
    {
        return [
            'Código','Grupo Contable', 'Descripcion',
            'Observaciones','Ciudad','Edificio','piso','Oficina',
            'Responsable','Cargo','Carnet'
        ];
    }
}
