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

class Form5Export implements FromCollection, WithHeadings, WithStyles, WithEvents, WithDrawings, WithCustomStartCell, WithColumnFormatting, WithTitle
{
    // ...

    public function title(): string
    {
        return 'CUADRO 5'; // nombre de la pestaña
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
        ];
    }
    public function startCell(): string
    {
        return 'A7'; // Aquí defines que los datos comiencen
    }
      public function columnFormats(): array
    {
        return [
            'C' => '#,##0.00', // Costo histórico
            'D' => '#,##0.00', // Costo histórico
            'E' => '#,##0.00', // Dep acumulada
            'F' => '#,##0.00', 
        ];
    }
    public function collection()
    {
        $gestion = $this->gestion;
        $entradas = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
        ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
        ->select('partidas.codigo','partidas.nompartida',
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad ELSE 0 END) as stock_inicial'),
            DB::raw('sum(entradas.restante) as stock_final'),
            DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
            DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final'))->where('entradas.anio','=', $gestion)
        ->groupBy('partidas.codigo', 'partidas.nompartida')->orderby('partidas.codigo', 'asc')->get();

         $filtrados = $entradas->map(function ($item, $index) {
            $item->nro = $index + 1;
            return [ 
                $item->nro,
                $item->codigo,
                $item->stock_inicial,
                $item->stock_final,
                $item->total_inicio,
                $item->total_final,
             ];});
        return $filtrados;
    }
    public function headings(): array
    {
        // Primera fila (encabezados agrupados)
        return [
                'Nro.',
                'Partida',
                'Cantidad Inicial al 01/01/'.$this->gestion,
                'Saldo Inicial al 01/01/'.$this->gestion.' (Bs)',
                'Cantidad Final al 31/12/'.$this->gestion,
                'Saldo Final al 31/12/'.$this->gestion.' (Bs)',
        ];

    }
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insertar título
                $sheet->setCellValue('A2', 'POLICIA BOLIVIANA');
                $sheet->mergeCells('A2:F2'); // Ajusta según tus columnas
                $sheet->getStyle('A2')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A3', 'DIRECCIÓN NACIONAL DE SALUD Y BIENESTAR SOCIAL');
                $sheet->mergeCells('A3:F3'); // Ajusta según tus columnas
                $sheet->getStyle('A3')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A4', 'RESUMEN DE ALMACENES (BIENES DE CONSUMO)');
                $sheet->mergeCells('A4:F4'); // Ajusta según tus columnas
                $sheet->getStyle('A4')->getFont()->setBold(true)->setUnderline(true)->setSize(12);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A5', 'Al 31 de diciembre de la gestión '.$this->gestion);
                $sheet->mergeCells('A5:F5'); // Ajusta según tus columnas
                $sheet->getStyle('A5')->getFont()->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A6', '(Expresado en Bolivianos) ');
                $sheet->mergeCells('A6:F6'); // Ajusta según tus columnas
                $sheet->getStyle('A6')->getFont()->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getDefaultRowDimension()->setRowHeight(-1);
                $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(25); 
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(25);
                
                $rowCount = $sheet->getHighestRow();
                 // Fórmula para sumar la columna de costo histórico (columna O = 15)
                
                // Etiqueta "Totales"
                $sheet->mergeCells('A'.($rowCount+2).':F'.($rowCount+2));
                $sheet->getRowDimension($rowCount+2)->setRowHeight(30);
                $sheet->getStyle('A'.($rowCount+2).':F'.$rowCount+2)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('A'.($rowCount+2), 'Nota: La información expuesta en el presente cuadro cuenta con la documentación de soporte correspondiente, en el marco de las Normas Básicas del Sistema de Contabilidad Integrada.');
                $sheet->mergeCells('A'.($rowCount+9).':B'.($rowCount+9));
                $sheet->setCellValue('A'.($rowCount+9), '......................................');
                $sheet->mergeCells('A'.($rowCount+10).':B'.($rowCount+10));
                $sheet->setCellValue('A'.($rowCount+10), 'Firma Contabilidad');
                $sheet->mergeCells('C'.($rowCount+9).':D'.($rowCount+9));
                $sheet->getStyle('C'.($rowCount+9).':D'.($rowCount+9))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('C'.($rowCount+9), '......................................');
                $sheet->getStyle('C'.($rowCount+10).':D'.($rowCount+10))->getAlignment()->setHorizontal('center');
                $sheet->mergeCells('C'.($rowCount+10).':D'.($rowCount+10));
                $sheet->setCellValue('C'.($rowCount+10), 'Firma Responsable');
                $sheet->mergeCells('E'.($rowCount+9).':F'.($rowCount+9));
                $sheet->getStyle('E'.($rowCount+9).':F'.($rowCount+9))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('E'.($rowCount+9), '......................................');
                $sheet->mergeCells('E'.($rowCount+10).':F'.($rowCount+10));
                $sheet->getStyle('E'.($rowCount+10).':F'.($rowCount+10))->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('E'.($rowCount+10), 'Firma DGAA - DAF');
                $sheet->mergeCells('A'.($rowCount+18).':F'.($rowCount+18));
                $sheet->getRowDimension($rowCount+18)->setRowHeight(40);
                $sheet->getStyle('A'.($rowCount+18).':F'.$rowCount+18)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('A'.($rowCount+18), 'Nota: Las entidades del sector público, que NO esten comprendidas dentro del Órgano Ejecutivo (Vicepresidencia, Ministerios de Estado y Tesoro General de la Nación), deben dar cumplimiento al Artículo 46 de las NBSCI, respecto a las firmas de los Estados Financieros  y estados de cuenta o información complementaria..');
                $sheet->getStyle('A7:F'.($rowCount+1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle('A'.($rowCount+1).':N'.($rowCount+1))->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'bold' => true,
                    ],
                ]);
                foreach (['C','D','E','F'] as $col) {
                    $sheet->getStyle($col.($rowCount+1))
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00');
                }
                $sheet = $event->sheet;
                $sheet->getDelegate()->setShowGridlines(false);

                $sheet->getStyle('A7:F7')->getFont()->setBold(true);
                $sheet->getStyle('A7:F7')->getAlignment()->setVertical('center');
                $sheet->getStyle('A7:F7')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A8:F8')->getAlignment()->setVertical('center');
                $sheet->getStyle('A:B')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A:F')->getAlignment()->setVertical('center');

                $sheet->setCellValue('B'.$rowCount+1, 'Totales:');
                $sheet->setCellValue('C'.$rowCount+1, "=SUM(C8:C".($rowCount).")");
                $sheet->setCellValue('D'.$rowCount+1, "=SUM(D8:D".($rowCount).")");
                $sheet->setCellValue('E'.$rowCount+1, "=SUM(E8:E".($rowCount).")");
                $sheet->setCellValue('F'.$rowCount+1, "=SUM(F8:F".($rowCount).")");
            }, 
        ];
    }
}
