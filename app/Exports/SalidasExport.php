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
       
     $entradas = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
    ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
    ->select(
        'partidas.codigo',
        'partidas.nompartida',
        'articulos.codigo as codigo_articulo',
        'articulos.descripcion as descripcion_articulo',
        DB::raw('sum(entradas.cantidad) as cantidad'),
        DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad ELSE 0 END) as stock_inicial'),
        DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad ELSE 0 END) as compras'),
        DB::raw('sum(entradas.restante) as stock_final'),
        DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
        DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final')
    )
    ->where('entradas.anio', '=', $gestion)
    ->groupBy(
        'partidas.codigo',
        'partidas.nompartida',
        'articulos.codigo',
        'articulos.descripcion'
    )->get();

$salidas = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
    ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
    ->select(
        'partidas.codigo',
        'partidas.nompartida',
        'articulos.codigo as codigo_articulo',
        'articulos.descripcion as descripcion_articulo',
        DB::raw('sum(salidas.cantidad) as cantidad')
    )
    ->where('salidas.anio', '=', $gestion)
    ->groupBy(
        'partidas.codigo',
        'partidas.nompartida',
        'articulos.codigo',
        'articulos.descripcion'
    )->get();

// Indexar salidas por código de artículo
$salidasIndexadas = $salidas->keyBy('codigo_articulo');

// Combinar resultados
$resultado = [];

foreach ($entradas as $entrada) {
    $codigo = $entrada->codigo_articulo;
    $salida = $salidasIndexadas->get($codigo);

    $resultado[] = [
        'codigo_partida' => $entrada->codigo,
        'nompartida' => $entrada->nompartida,
        'codigo_articulo' => $codigo,
        'descripcion_articulo' => $entrada->descripcion_articulo,
        'cantidad_entrada' => $entrada->cantidad,
        'stock_inicial' => $entrada->stock_inicial,
        'compras' => $entrada->compras,
        'stock_final' => $entrada->stock_final,
        'total_inicio' => $entrada->total_inicio,
        'total_final' => $entrada->total_final,
        'cantidad_salida' => $salida ? $salida->cantidad : 0,
    ];
}

// Agregar artículos que solo están en salidas
foreach ($salidas as $salida) {
    if (!$entradas->contains('codigo_articulo', $salida->codigo_articulo)) {
        $resultado[] = [
            'codigo_partida' => $salida->codigo,
            'nompartida' => $salida->nompartida,
            'codigo_articulo' => $salida->codigo_articulo,
            'descripcion_articulo' => $salida->descripcion_articulo,
            'cantidad_entrada' => 0,
            'stock_inicial' => 0,
            'compras' => 0,
            'stock_final' => 0,
            'total_inicio' => 0,
            'total_final' => 0,
            'cantidad_salida' => $salida->cantidad,
        ];
    }
}
            return collect($resultado);
    }
    public function headings(): array
    {
        return [
            'Código', 'Nombre Partida', 'Código Artículo', 'Descripción Artículo', 'Cantidad', 'Precio Unitario',
            'Stock Inicial', 'Compras',  'Stock Final', 'Total Inicio', 'Total Final'
        ];
    }

}
