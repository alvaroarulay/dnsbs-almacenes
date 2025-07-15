<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Provedores;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Ciudad;

use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class ProvedoresController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar=='') {
            $provedores = Provedores::paginate(10);
        } else {
            $provedores = Provedores::where($criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $provedores->total(),
                'current_page'  => $provedores->currentPage(),
                'per_page'      => $provedores->perPage(),
                'last_page'     => $provedores->lastPage(),
                'from'          => $provedores->firstItem(),
                'to'            => $provedores->lastItem(),
            ],
            'provedores' => $provedores
        ];
       
    }
    public function buscar($nit){
      return response()->json(Provedores::where('nit','=',$nit)->get());
    }
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nompro' => 'nullable|max:50',
                'nit' => 'nullable|max:20',
                'telefono' => 'nullable|max:20',
                'direccion' => 'nullable|max:100',
                'email' => 'nullable|email|max:50'
            ]);
        
            $provedor = new Provedores();
            $provedor->nompro = $request->nompro;
            $provedor->direccion = $request->direccion;
            $provedor->nit = $request->nit;
            $provedor->telefono = $request->telefono;
            $provedor->contacto = $request->contacto;
            $provedor->email = $request->email;
            $provedor->observaciones = $request->observ;
            $provedor->save();
            return response()->json(['message' => 'Provedor guardado correctamente!']);
       } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
           
            $provedor = Provedores::findOrFail($request->id);
            $provedor->nompro = $request->nompro;
            $provedor->direccion = $request->direccion;
            $provedor->nit = $request->nit;
            $provedor->telefono = $request->telefono;
            $provedor->contacto = $request->contacto;
            $provedor->email = $request->email;
            $provedor->observaciones = $request->observ;
            $provedor->save();
            return response()->json(['message' => 'Provedor actualizado correctamente!']);

        }  catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
        if (!$id) {
            return response()->json(['message' => 'ID de provedor no proporcionado'], 400);
        }
        if (!Provedores::find($id)) {
            return response()->json(['message' => 'provedor no encontrada'], 404);    
        }
        // Verificar si la unidad está siendo utilizada en otra tabla
        /*$relatedRecords = \DB::table('productos')->where('unidad_id', $id)->exists();
        if ($relatedRecords) {
            return response()->json(['message' => 'No se puede eliminar la Unidad porque está siendo utilizada en otra tabla.'], 400);
        }*/
        // Si no hay registros relacionados, proceder a eliminar la unidad
        
            $provedor = Provedores::findOrFail($id);
            $provedor->delete();
        return response()->json(['message' => 'Provedor eliminado correctamente!']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar el Provedor porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar el Provedor porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function pdfProvedores(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format('l j F Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Provedores::orderBy('id', 'asc')->get();
        $titulo = 'Listado de Provedores';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();;
        $pdf=Pdf::loadView('plantillapdf.reporteprovedores',[
            'datos'=>$datos,
            'titulo'=>$titulo,
            'fechaTitulo'=>$fechaTitulo,
            'fechaDerecha'=>$fechDerecha,
            'almacen'=>$almacen->nomalmacen,
            'establecimiento'=>$establecimiento->nomestab,
            'ciudad'=>$ciudad->nomciudad,
            ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        
    }
}
