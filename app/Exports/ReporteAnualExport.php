<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteAnualExport implements WithMultipleSheets
{
    protected $gestion;

    public function __construct($gestion)
    {
        $this->gestion = $gestion;
    }
    public function sheets(): array
    {
        return [
            new Form5Export($this->gestion),
            new SalidasExport($this->gestion),
        ];
    }

}
