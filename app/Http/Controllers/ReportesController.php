<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportesController extends Controller
{
    public function exportarxls(Request $request){
        $ciudad = $request->ciudad;
        $fechai = Carbon::parse($request->fechai)->startOfDay();
        $fechaf = Carbon::parse($request->fechaf)->endOfDay();
        return Excel::download(new ReportExport($ciudad,$fechai,$fechaf), 'reporte.xlsx');
    }
}
