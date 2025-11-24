<?php

namespace App\Http\Controllers;
use App\Models\Articulos;
use App\Models\Partidas;
use Barryvdh\DomPDF\Facade\Pdf;
//use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Entradas;
use App\Models\Ciudad;
use App\Models\Salidas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;
class ArticulosController extends Controller
{
     public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $partida = $request->partida_id;
        $query = Articulos::join('partidas', 'articulos.id_partida', '=', 'partidas.id')
                            ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
                            ->join('almacen', 'articulos.id_almacen', '=', 'almacen.id')
            ->select('articulos.*','partidas.codigo as codigo_partida','partidas.nompartida','unidades.nomunidad as unidad_nombre','almacen.nomalmacen as almacen_nombre','partidas.id as id_partida','unidades.id as id_unidad')
            ->groupBy('articulos.id','partidas.nompartida','unidades.nomunidad','almacen.nomalmacen','partidas.id','unidades.id')->orderBy('articulos.codigo', 'asc');
        if ($partida != 0) {
            $articulos = $query->where('articulos.id_partida','=', $partida)->paginate(10);
        }
        if ($buscar=='') {
            $articulos = $query->paginate(10);
        } else {    
            $articulos = $query->where('articulos.'.$criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
       
    }
     public function stock(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Articulos::join('partidas', 'articulos.id_partida', '=', 'partidas.id')
                            ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
                            ->join('almacen', 'articulos.id_almacen', '=', 'almacen.id')
                            ->join('entradas', 'articulos.id', '=', 'entradas.id_articulo')
            ->select('articulos.*','partidas.nompartida as partida_nombre','unidades.nomunidad as unidad_nombre',DB::raw('sum(entradas.restante) as stock'),
                    'almacen.nomalmacen as almacen_nombre','partidas.id as id_partida','unidades.id as id_unidad')
            ->groupBy('articulos.id','partidas.nompartida','unidades.nomunidad','almacen.nomalmacen','partidas.id','unidades.id')
            ->havingRaw('SUM(entradas.restante) > 0')
            ->orderBy('articulos.codigo', 'asc');
        
        if ($buscar=='') {
            $articulos = $query->paginate(10);
        } else {
            $articulos = $query->where('articulos.'.$criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
       
    }
    public function buscar($cod){
        $anio =  date('Y');
        try{
            $articulo = Articulos::join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
            ->select('articulos.*','unidades.nomunidad as unidad_nombre')
            ->where('codigo','=',$cod)->get();
            if($articulo->count()==0){
                return response()->json(['message'=>'No se encontro Articulo']); 
            }
            $stock = Entradas::select('restante')->where('id_articulo','=',$articulo[0]->id)->where('anio','=',$anio)->sum('restante');
            return response()->json(['articulo'=>$articulo,'stock'=>$stock]);  
        }catch(Exception $e){
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function partidas($id){
        $query = Articulos::where('id_partida','=',$id)->count()+1;
        $partida = Partidas::where('id', '=', $id)->value('codigo');
        $partidas = $partida . '-' . $query;
        return response()->json(['partidas'=>$partidas]);
    }
    public function store(Request $request)
    {
        try {
            $almacen = Almacenes::where('seleccionado','=',1)->first();
            $this->validate($request, [
                'codigo' => 'required|unique:articulos,codigo',
                'codanterior' => 'required|max:100',
                'descripcion' => 'required|max:255',
            ]);
            $articulo = new Articulos();
            $articulo->cod_anterior = $request->codanterior;
            $articulo->codigo = $request->codigo;
            $articulo->descripcion = $request->descripcion;
            $articulo->fecha_expiracion = $request->fecha_expiracion;
            $articulo->id_partida = $request->partida_id;
            $articulo->id_unidad = $request->unidad_id;
            $articulo->id_almacen = $almacen->id;
            $articulo->save();
            return response()->json(['message' => 'Articulo guardado correctamente!']);
         } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
        
            if (!$request->id) {
                return response()->json(['message' => 'ID de articulo no proporcionado'], 400);
            }
            $articulo = Articulos::findOrFail($request->id);
            $articulo->cod_anterior = $request->codanterior;
            $articulo->codigo = $request->codigo;
            $articulo->descripcion = $request->descripcion;
            $articulo->fecha_expiracion = $request->fecha_expiracion;
            $articulo->id_partida = $request->partida_id;
            $articulo->id_unidad = $request->unidad_id;
            $articulo->save();
            return response()->json(['message' => 'Articulo actualizado correctamente!']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $articulo = Articulos::findOrFail($id);
            $articulo->delete();
            return response()->json(['message' => 'Articulo eliminado correctamente!']);
        }  catch (\Illuminate\Database\QueryException $e) {
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
    /**
     * Genera un reporte PDF con el listado de artículos.
     *
     * Este método obtiene los datos de los artículos, incluyendo información relacionada
     * de partidas, unidades y almacenes, y los pasa a una vista para generar un PDF.
     * El PDF incluye el nombre del almacén y establecimiento, así como la fecha actual
     * en diferentes formatos para el título y la cabecera.
     *
     * @return \Illuminate\Http\Response El archivo PDF generado como respuesta en el navegador.
     */
     public function pdfArticulos(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format(' j \\de F \\de Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                            ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
                            ->join('almacen', 'articulos.id_almacen', '=', 'almacen.id')
            ->select(
        'articulos.*',
        'unidades.nomunidad as unidad',
        'almacen.nomalmacen as almacen',
        DB::raw('SUM(entradas.restante) as stock'),
        DB::raw('ROUND(AVG(entradas.precio_unitario),2) as precio_unitario'),
        DB::raw('ROUND(SUM(entradas.restante * entradas.precio_unitario),2) as total'))
        ->where('almacen.seleccionado','=',1)
        ->groupBy('articulos.id','unidades.nomunidad','almacen.nomalmacen')
        ->havingRaw('SUM(entradas.restante) > 0')
        ->orderBy('articulos.codigo', 'asc')->get();

        $titulo = 'INVENTARIO ACTUAL';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
        $pdf=Pdf::loadView('plantillapdf.reporteartxalmacen',[
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
