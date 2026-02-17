<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
     protected $ciudad;
    protected $fechai;
    protected $fechaf;

    public function __construct($ciudad,$fechai,$fechaf)
    {
        $this->ciudad = $ciudad;
        $this->fechai = $fechai;
        $this->fechaf = $fechaf;
    }
    public function sheets(): array
    {
        return [
            new ReportselExport($this->ciudad,$this->fechai,$this->fechaf),
        ];
    }

}