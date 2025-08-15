<?php

namespace App\Exports;
use App\Models\Salidas;
use App\Models\Entradas;
use App\Models\Articulos;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalidasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $gestion;

    public function __construct($gestion)
    {
        $this->gestion = $gestion;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], 
        ];
    }
    public function collection()
    {
        $gestion = $this->gestion;
       
        $entrada = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
            ->select(
                //'entradas.id',
               // 'entradas.fecha',
                //'entradas.numero_anual',
                'partidas.codigo',
                'partidas.nompartida',
                'articulos.codigo as codigo_articulo',
                'articulos.descripcion as descripcion_articulo',
                'entradas.cantidad',
                'entradas.precio_unitario',
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad ELSE 0 END) as stock_inicial'),
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad ELSE 0 END) as compras'),
                DB::raw('sum(entradas.restante) as stock_final'),
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
                DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final')
            )
            ->where('entradas.anio', '=', $gestion)
            ->groupBy(
                //'entradas.id',
                //'entradas.fecha',
                //'entradas.numero_anual',
                'partidas.codigo',
                'partidas.nompartida',
                'articulos.codigo',
                'articulos.descripcion',
                'entradas.cantidad',
                'entradas.precio_unitario'
            )->get();

            return collect($entrada);
    }
    public function headings(): array
    {
        return [
            'Código', 'Nombre Partida', 'Código Artículo', 'Descripción Artículo', 'Cantidad', 'Precio Unitario',
            'Stock Inicial', 'Compras',  'Stock Final', 'Total Inicio', 'Total Final'
        ];
    }

}
