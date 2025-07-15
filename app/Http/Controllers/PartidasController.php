<?php

namespace App\Http\Controllers;
use App\Models\Partidas;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Almacenes;
use App\Models\Establecimiento;

use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class PartidasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar=='') {
            $partidas = Partidas::paginate(10);
        } else {
            $partidas = Partidas::where($criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $partidas->total(),
                'current_page'  => $partidas->currentPage(),
                'per_page'      => $partidas->perPage(),
                'last_page'     => $partidas->lastPage(),
                'from'          => $partidas->firstItem(),
                'to'            => $partidas->lastItem(),
            ],
            'partidas' => $partidas
        ];
       
    }
    public function todos()
    {
        return response()->json(Partidas::all());
    }
    public function store(Request $request)
    {
        $partida = new Partidas();
        $partida->codigo = $request->codigo;
        $partida->nompartida = $request->nompartida;
        $partida->save();
        return response()->json(['message' => 'Partida guardada correctamente!']);
    }
    public function update(Request $request)
    {
        $partida = Partidas::findOrFail($request->id);
        $partida->codigo = $request->codigo;
        $partida->nompartida = $request->nompartida;
        $partida->save();
        return response()->json(['message' => 'Partida actualizada correctamente!']);
    }
    public function destroy($id)
    {
        try {
            $partida = Partidas::findOrFail($id);
        $partida->delete();
        return response()->json(['message' => 'Partida eliminada correctamente!']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar la Partida porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar el registro porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    /**
     * Deactivate or activate a Partida
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function desactivar(Request $request)
    {
        $partida = Partidas::findOrFail($request->id);
        $partida->condicion = '0';
        $partida->save();
        return response()->json(['message' => 'Partida desactivada correctamente!']);
    }
    public function activar(Request $request)
    {
        $partida = Partidas::findOrFail($request->id);
        $partida->condicion = '1';
        $partida->save();
        return response()->json(['message' => 'Partida activada correctamente!']);
    }
    /**
     * Generate PDF report for Partidas
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfPartidas(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format('l j F Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Partidas::orderBy('id', 'asc')->get();
        $titulo = 'Listado de Partidas';
        $almacen = Almacenes::find(1);
        $establecimiento = Establecimiento::find(1);
        $pdf=Pdf::loadView('plantillapdf.reporte',[
            'datos'=>$datos,
            'titulo'=>$titulo,
            'fechaTitulo'=>$fechaTitulo,
            'fechaDerecha'=>$fechDerecha,
            'almacen'=>$almacen->nomalmacen,
            'establecimiento'=>$establecimiento->nomestab,
            ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        
    }
}
