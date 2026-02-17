<?php

namespace App\Exports;
use App\Models\Salidas;
use App\Models\Entradas;
use App\Models\Articulos;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
//use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Jenssegers\Date\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalidasExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithDrawings, WithCustomStartCell, WithColumnFormatting, WithTitle
{
    // ...

    public function title(): string
    {
        return 'CUADRO 6'; // nombre de la pestaña
    }
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
            7 =>  [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE], // Color de fuente
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '304421'], // Color de fondo (azul institucional)
                ],
            ],
              8 =>  [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => Color::COLOR_WHITE], // Color de fuente
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '304421'], // Color de fondo (azul institucional)
                ],
            ],
 
        ];
    }
    public function startCell(): string
    {
        return 'A7'; // Aquí defines que los datos comiencen en A14
    }
      public function columnFormats(): array
    {
        return [
            'F' => '#,##0.00', // Costo histórico
            'G' => '#,##0.00', // Costo histórico
            'H' => '#,##0.00', // Dep acumulada
            'I' => '#,##0.00', // Dep acumulada
            'J' => '#,##0.00', // Valor neto
            'K' => '#,##0.00', // Valor neto
            'L' => '#,##0.00', // Valor neto
            'M' => '#,##0.00', // Valor neto
            'N' => '#,##0.00', // Dep acumulada
        ];
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
            DB::raw('sum(entradas.restante) as stock_final'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_entradas'),
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
        $salidasIndexadas = $salidas->keyBy('id_articulo');
        $resultado = [];
        $nro=0;
            foreach ($entradas as $entrada) {
                $id = $entrada->id_articulo;
                $salida = $salidasIndexadas->get($id);
                $nro+=1;
                $resultado[] = [
                    'nro'=>$nro,
                    'partida' => $entrada->partida_full,
                    'codigo_articulo' => $entrada->codigo_articulo,
                    'descripcion_articulo' => $entrada->descripcion_articulo,
                    'unidad_medida' => $entrada->unidad_medida,
                    'precio_unitario' => $entrada->precio_unitario,
                    'cantidad_inicial' => $entrada->stock_inicial,
                    'entradas' => $entrada->cantidad,
                    'salidas' => (isset($salida) && isset($salida->total_salidas) && $salida->total_salidas != 0) ? $salida->total_salidas : '0',
                    'cantidad_final' => $entrada->stock_final,
                    'saldo_inicial' => $entrada->total_inicio,
                    'entradas_bs' => $entrada->total_entradas,
                    'salidas_bs' =>  (isset($salida) && isset($salida->total_movimientos) && $salida->total_movimientos != 0) ? $salida->total_movimientos : '0',
                    'total_final_bs' => $entrada->total_final,
                ];
            }
        $coleccion = collect($resultado);

        $final = $coleccion->groupBy('partida')->flatMap(function ($items, $partida) {
            $filas = $items->values()->toArray();

            $totales = [
                'nro' => '',
                'partida' => $partida . ' (TOTAL)',
                'codigo_articulo' => '',
                'descripcion_articulo' => '',
                'unidad_medida' => '',
                'precio_unitario' => '',
                'cantidad_inicial' => $items->sum('cantidad_inicial'),
                'entradas' => $items->sum('entradas'),
                'salidas' => $items->sum(function($i){ return (int)$i['salidas']; }),
                'cantidad_final' => $items->sum('cantidad_final'),
                'saldo_inicial' => $items->sum('saldo_inicial'),
                'entradas_bs' => $items->sum('entradas_bs'),
                'salidas_bs' => $items->sum(function($i){ return (float)$i['salidas_bs']; }),
                'total_final_bs' => $items->sum('total_final_bs')
            ];

            return array_merge($filas, [$totales]);
        });
        $totalGeneral = [
            'nro' => '',
            'partida' => 'SUMA TOTAL GENERAL',
            'codigo_articulo' => '',
            'descripcion_articulo' => '',
            'unidad_medida' => '',
            'precio_unitario' => '',
            'cantidad_inicial' => $coleccion->sum('cantidad_inicial'),
            'entradas' => $coleccion->sum('entradas'),
            'salidas' => $coleccion->sum(function($i){ return (int)$i['salidas']; }),
            'cantidad_final' => $coleccion->sum('cantidad_final'),
            'saldo_inicial' => $coleccion->sum('saldo_inicial'),
            'entradas_bs' => $coleccion->sum('entradas_bs'),
            'salidas_bs' => $coleccion->sum(function($i){ return (float)$i['salidas_bs']; }),
            'total_final_bs' => $coleccion->sum('total_final_bs')
        ];

        $final->push($totalGeneral);
         return collect($final);
    }
    public function headings(): array
    {
        // Primera fila (encabezados agrupados)
        return [

            [
                'Nro.',
                'Partida',
                'Código',
                'Descripción (Ítem)',
                'Unidad de medida',
                'Precio Unitario (Bs.)','Cantidad','', '', '','Valores' 
            ],
            [
                '', '', '', '', '', '',
                'Saldo Inicial',
                'Entradas','Salidas','Saldo Final',
                'Saldo Inicial (Bs.)',
                'Entradas (Bs.)','Salidas (Bs.)','Saldo Final (Bs.)'
            ]
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
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getDefaultRowDimension()->setRowHeight(-1);
                $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(35); 
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(35);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(15);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(15);
                $sheet->getColumnDimension('L')->setWidth(15);
                $sheet->getColumnDimension('M')->setWidth(15); 
                $sheet->getColumnDimension('N')->setWidth(15); 
                
                $rowCount = $sheet->getHighestRow();
                 // Fórmula para sumar la columna de costo histórico (columna O = 15)
                
                // Etiqueta "Totales"
                $sheet->mergeCells('A'.($rowCount+2).':N'.($rowCount+2));
                $sheet->setCellValue('A'.($rowCount+2), 'Nota: La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.');
                $sheet->mergeCells('B'.($rowCount+9).':C'.($rowCount+9));
                $sheet->setCellValue('B'.($rowCount+9), '......................................');
                $sheet->mergeCells('B'.($rowCount+10).':C'.($rowCount+10));
                $sheet->setCellValue('B'.($rowCount+10), 'Firma Contabilidad');
                $sheet->mergeCells('F'.($rowCount+9).':G'.($rowCount+9));
                $sheet->getStyle('F'.($rowCount+9).':G'.($rowCount+9))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('F'.($rowCount+9), '......................................');
                $sheet->getStyle('F'.($rowCount+10).':G'.($rowCount+10))->getAlignment()->setHorizontal('center');
                $sheet->mergeCells('F'.($rowCount+10).':G'.($rowCount+10));
                $sheet->setCellValue('F'.($rowCount+10), 'Firma Responsable');
                $sheet->mergeCells('J'.($rowCount+9).':K'.($rowCount+9));
                $sheet->getStyle('J'.($rowCount+9).':K'.($rowCount+9))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('J'.($rowCount+9), '......................................');
                $sheet->mergeCells('J'.($rowCount+10).':K'.($rowCount+10));
                $sheet->getStyle('J'.($rowCount+10).':K'.($rowCount+10))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('J'.($rowCount+10), 'Firma DGAA - DAF');
                $sheet->mergeCells('A'.($rowCount+18).':N'.($rowCount+18));
                $sheet->setCellValue('A'.($rowCount+18), 'Nota: Las entidades del sector público, que NO esten comprendidas dentro del Órgano Ejecutivo (Vicepresidencia, Ministerios de Estado y Tesoro General de la Nación), deben dar cumplimiento al');
                $sheet->mergeCells('B'.($rowCount+19).':N'.($rowCount+19));
                $sheet->setCellValue('B'.($rowCount+19), 'Artículo 46 de las NBSCI, respecto a las firmas de los Estados Financieros  y estados de cuenta o información complementaria..');
                $sheet->getStyle('A7:N'.($rowCount))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle('A'.($rowCount).':N'.($rowCount))->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'bold' => true,
                    ],
                ]);
                foreach (['E','F','G','H','I','J','K','L','M','N'] as $col) {
                    $sheet->getStyle($col.($rowCount+1))
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                }
                $sheet = $event->sheet;
                $sheet->mergeCells('A7:A8');
                $sheet->mergeCells('B7:B8');
                $sheet->mergeCells('C7:C8');
                $sheet->mergeCells('D7:D8');
                $sheet->mergeCells('E7:E8');
                $sheet->mergeCells('F7:F8');
                $sheet->mergeCells('G7:J7'); // "CANTIDAD"
                $sheet->mergeCells('K7:N7'); // "CANTIDAD"
                 // "VALORES"
                $sheet->getDelegate()->setShowGridlines(false);
                // También puedes ajustar estilos
                $sheet->getStyle('A7:N7')->getFont()->setBold(true);
                $sheet->getStyle('A7:N7')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A7:N7')->getAlignment()->setVertical('center');
                $sheet->getStyle('A8:N8')->getFont()->setBold(true);
                $sheet->getStyle('A8:N8')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A8:N8')->getAlignment()->setVertical('center');
                $sheet->getStyle('A:E')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A:N')->getAlignment()->setVertical('center');
            }, 
        ];
    }
}
