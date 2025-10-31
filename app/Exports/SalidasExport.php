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
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;



class SalidasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithDrawings, WithCustomStartCell
{
    protected $gestion;

    public function __construct($gestion)
    {
        $this->gestion = $gestion;
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo institucional');
        $drawing->setPath(public_path('img/logoinstitucional.jpg')); // Ruta al logo
        $drawing->setHeight(80); // Ajusta el tamaño
        $drawing->setCoordinates('A1'); // Posición en la hoja

        return [$drawing];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            8 =>  [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE], // Color de fuente
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4A90E2'], // Color de fondo (azul institucional)
                ],
            ],
 
        ];
    }
    public function startCell(): string
    {
        return 'A8'; // Aquí defines que los datos comiencen en A14
    }
    public function collection()
    {
        $gestion = $this->gestion;
        $entradas = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
        ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
        ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
        ->select(
            'articulos.id as id_articulo',
            DB::raw("CONCAT(partidas.codigo, ' - ', partidas.nompartida) as partida_full"),
            'articulos.codigo as codigo_articulo',
            'articulos.descripcion as descripcion_articulo',
            'unidades.nomunidad as unidad_medida',
            DB::raw('AVG(entradas.precio_unitario) as precio_unitario'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad ELSE 0 END) as stock_inicial'), 
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad ELSE 0 END) as cantidad'),
            //DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad ELSE 0 END) as compras'),
            //salidas
            DB::raw('sum(entradas.restante) as stock_final'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_entradas'),
            //total salidas
            DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final')
        )
        ->where('entradas.anio', '=', $gestion)
        ->groupBy(
            'articulos.id',
            'partidas.codigo',
            'partidas.nompartida',
            'articulos.codigo',
            'unidades.nomunidad',
            'articulos.descripcion'
        )->orderBy('articulos.codigo')->get();

        $salidas = DB::table(DB::raw('(SELECT id_articulo, anio, SUM(cantidad) as total_salidas FROM salidas GROUP BY id_articulo, anio) as salidas_sum'))
            ->join('articulos', 'salidas_sum.id_articulo', '=', 'articulos.id')
            ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
            ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
            ->leftJoin('salidas', function($join) {
                $join->on('salidas.id_articulo', '=', 'salidas_sum.id_articulo')
                    ->on('salidas.anio', '=', 'salidas_sum.anio');
            })
            ->leftJoin('movimientos', 'movimientos.id_salida', '=', 'salidas.id')
            ->select(
                'articulos.id as id_articulo',
                DB::raw("CONCAT(partidas.codigo, ' - ', partidas.nompartida) as partida_full"),
                'articulos.codigo as codigo_articulo',
                'articulos.descripcion as descripcion_articulo',
                'unidades.nomunidad as unidad_medida',
                'salidas_sum.total_salidas',
                DB::raw('SUM(movimientos.precio_unitario * movimientos.cantidad_utilizada) as total_movimientos')
            )
            ->where('salidas_sum.anio', '=', $gestion)
            ->groupBy(
                'articulos.id',
                'partidas.codigo',
                'partidas.nompartida',
                'articulos.codigo',
                'articulos.descripcion',
                'unidades.nomunidad',
                'salidas_sum.total_salidas'
            )
            ->orderBy('articulos.codigo')
            ->get();
        // Indexar salidas por código de artículo
        $salidasIndexadas = $salidas->keyBy('id_articulo');

        // Combinar resultados
        $resultado = [];

            foreach ($entradas as $entrada) {
                $id = $entrada->id_articulo;
                $salida = $salidasIndexadas->get($id);

                $resultado[] = [
                    'partida' => $entrada->partida_full,
                    'codigo_articulo' => $entrada->codigo_articulo,
                    'descripcion_articulo' => $entrada->descripcion_articulo,
                    'unidad_medida' => $entrada->unidad_medida,
                    'precio_unitario' => $entrada->precio_unitario,
                    'cantidad_inicial' => $entrada->stock_inicial,
                    'entradas' => $entrada->cantidad,
                    'salidas' => (isset($salida) && isset($salida->total_salidas) && $salida->total_salidas != 0) ? $salida->total_salidas : 0,
                    'cantidad_final' => $entrada->stock_final,
                    'saldo_inicial' => $entrada->total_inicio,
                    'entradas_bs' => $entrada->total_entradas,
                    'salidas_bs' =>  (isset($salida) && isset($salida->total_movimientos) && $salida->total_movimientos != 0) ? $salida->total_movimientos : 0,
                    'total_final_bs' => $entrada->total_final,
                ];
            }
            
        /* Agregar artículos que solo están en salidas
        foreach ($salidas as $salida) {
            if (!$entradas->contains('codigo_articulo', $salida->codigo_articulo)) {
                $resultado[] = [
                    'id'=>$salida->id,
                    'partida' => $salida->partida_full,
                    'codigo_articulo' => $codigo_articulo,
                    'descripcion_articulo' => $salida->descripcion_articulo,
                    'unidad_medida' => $salida->unidad_medida,
                    'cantidad_inicial' => 0,
                    'entradas' => 0,
                    'salidas' => $salida ? $salida->total_salidas : 0,
                    'cantidad_final' => 0,
                    'saldo_inicial' => 0,
                    'entradas_bs' => 0,
                // 'salidas_bs' => $salida ? $salida->total_movimientos : 0,
                    'total_final_bs' =>0,
                ];
            }
        }*/
                return collect($resultado);
    }
    public function headings(): array
    {
        return [
            'Partida',
            'Código Artículo', 
            'Descripción Artículo',
            'Unidad de Medida',
            'Precio Unitario',
            'Cantidad al Inicio',
            'Entradas',
            'Salidas',
            'Cantidad al Final de la Gestión',
            'Saldo Inicial a la Gestión',
            'Entradas en Bs.',
            'Salidas en Bs.',
            'Total al Final de la Gestión en Bs.'
        ];
    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insertar título
                $sheet->setCellValue('A2', 'POLICIA BOLIVIANA');
                $sheet->mergeCells('A2:N2'); // Ajusta según tus columnas
                $sheet->getStyle('A2')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A3', 'DIRECCIÓN NACIONAL DE SALUD Y BIENESTAR SOCIAL');
                $sheet->mergeCells('A3:N3'); // Ajusta según tus columnas
                $sheet->getStyle('A3')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A4', 'DETALLE DE ALMACENES (BIENES DE CONSUMO)');
                $sheet->mergeCells('A4:N4'); // Ajusta según tus columnas
                $sheet->getStyle('A4')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A5', 'Al 31 de diciembre de la gestión '.$this->gestion);
                $sheet->mergeCells('A5:N5'); // Ajusta según tus columnas
                $sheet->getStyle('A5')->getFont()->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A6', '(Expresado en Bolivianos) ');
                $sheet->mergeCells('A6:N6'); // Ajusta según tus columnas
                $sheet->getStyle('A6')->getFont()->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
