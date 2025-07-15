<?php

namespace App\Http\Controllers;
use App\Models\Unidades;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Partidas;

use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class UnidadesController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Unidades::join('partidas', 'unidades.id_partida', '=', 'partidas.id')
            ->select('unidades.*', 'partidas.id as idpartida','partidas.codigo as codigo_partida','partidas.nompartida as partida');
        if ($buscar=='') {
            $unidades = $query->orderby('id','asc')->paginate(10);
        } else {
            $unidades = $query->where($criterio, 'like', '%' . $buscar . '%')->orderby('id','asc')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $unidades->total(),
                'current_page'  => $unidades->currentPage(),
                'per_page'      => $unidades->perPage(),
                'last_page'     => $unidades->lastPage(),
                'from'          => $unidades->firstItem(),
                'to'            => $unidades->lastItem(),
            ],
            'unidades' => $unidades
        ];
       
    }
    public function todos($id)
    {
        if ($id) {
            $unidades = Unidades::where('id_partida', $id)->get();
            return response()->json($unidades);
        }else{
            $unidades = Unidades::all();
            return response()->json($unidades);
        }
    }
    public function store(Request $request)
    {
        try {
             $this->validate($request, [
                'nomunidad' => 'required|unique:unidades,nomunidad',
                'sigla' => 'nullable|max:10',
                'idpartida' => 'required|exists:partidas,id'
            ]);
        
            $unidad = new Unidades();
            $unidad->nomunidad = $request->nomunidad;
            $unidad->sigla = $request->sigla;
            $unidad->id_partida = $request->idpartida;
            $unidad->save();
            return response()->json(['message' => 'Unidad guardada correctamente!']);
        }  catch (\Illuminate\Validation\ValidationException $e) {
            // Retornar errores de validación correctamente
            return response()->json([
                'message' => 'ya existe una registro con este nombre!.',
                'errors' => $e->errors()
            ], 422);
        }
    }
    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'nomunidad' => 'required|unique:unidades,nomunidad,' . $request->id,
                'sigla' => 'nullable|max:10',
                'idpartida' => 'required|exists:partidas,id'
            ]);
            if (!$request->id) {
                return response()->json(['message' => 'ID de unidad no proporcionado'], 400);
            }
            if (!Unidades::find($request->id)) {
                return response()->json(['message' => 'Unidad no encontrada'], 404);
            }   
            $unidad = Unidades::findOrFail($request->id);
            $unidad->nomunidad = $request->nomunidad;   
            $unidad->sigla = $request->sigla;
            $unidad->id_partida = $request->idpartida;
            $unidad->save();
            return response()->json(['message' => 'Unidad actualizada correctamente!']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
    public function destroy($id)
    {
        try {
            if (!$id) {
                return response()->json(['message' => 'ID de unidad no proporcionado'], 400);
            }
            if (!Unidades::find($id)) {
                return response()->json(['message' => 'Unidad no encontrada'], 404);    
            }
            $unidad = Unidades::findOrFail($id);
            $unidad->delete();
            return response()->json(['message' => 'Unidad eliminada correctamente!']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar la Unidad porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar el Unidad porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function pdfUnidades(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format('l j F Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Unidades::orderBy('id', 'asc')->get();
        $titulo = 'Listado de Unidades';
        $almacen = Almacenes::find(1);
        $establecimiento = Establecimiento::find(1);
        $pdf=Pdf::loadView('plantillapdf.reporteunidades',[
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
