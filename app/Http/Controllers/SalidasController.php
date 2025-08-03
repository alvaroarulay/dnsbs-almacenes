<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Salidas;
use App\Models\Entradas;
use App\Models\Personal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Ciudad;
use App\Models\Facturas;
use App\Models\Movimientos;
use Jenssegers\Date\Date;
use App\Exports\SalidasExport;
use Maatwebsite\Excel\Facades\Excel;

class SalidasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
            ->join('almacen','articulos.id_almacen','=','almacen.id')
            ->join('personal', 'salidas.id_personal', '=', 'personal.id')
            ->join('movimientos','salidas.id','=','movimientos.id_salida')
            ->join('entradas','movimientos.id_entrada','=','entradas.id')
            ->select('salidas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal','movimientos.precio_unitario','entradas.numero_anual as nota')
            ->where('almacen.seleccionado','=',1);
        if ($buscar=='') {
            $salidas = $query->paginate(10);
        } else {
            $salidas = $query->where('articulos.'.$criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $salidas->total(),
                'current_page'  => $salidas->currentPage(),
                'per_page'      => $salidas->perPage(),
                'last_page'     => $salidas->lastPage(),
                'from'          => $salidas->firstItem(),
                'to'            => $salidas->lastItem(),
            ],
            'salidas' => $salidas
        ];

    }
    public function notas(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
                ->join('almacen','articulos.id_almacen','=','almacen.id')
                ->join('personal','salidas.id_personal','=','personal.id')
                ->join('movimientos','salidas.id','=','movimientos.id_salida')
                ->select('salidas.numero_anual','salidas.anio','salidas.fecha','salidas.created_at','personal.nomper',
                DB::raw('sum(salidas.cantidad) as cantidad'),
                DB::raw('sum(salidas.cantidad * movimientos.precio_unitario) as total'))
                ->where('anio','=',$request->anio)
                ->groupBy('personal.nomper')
                ->groupBy('salidas.fecha')
                ->groupBy('salidas.anio')
                ->groupBy('salidas.created_at')
                ->groupBy('salidas.numero_anual')
                ->orderBy('salidas.numero_anual','asc');
        if ($buscar=='') {
            $salidas = $query->paginate(10);
        } else {
            $salidas = $query->where($criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $salidas->total(),
                'current_page'  => $salidas->currentPage(),
                'per_page'      => $salidas->perPage(),
                'last_page'     => $salidas->lastPage(),
                'from'          => $salidas->firstItem(),
                'to'            => $salidas->lastItem(),
            ],
            'salidas' => $salidas
        ];
    }
    public function items(Request $request){
        try{
             $query = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
                ->join('almacen','articulos.id_almacen','=','almacen.id')
                ->join('movimientos','movimientos.id_salida','=','salidas.id')
                ->select('articulos.codigo','articulos.descripcion','movimientos.precio_unitario','movimientos.cantidad_utilizada as cantidad')
                ->where('salidas.numero_anual','=',$request->nro)
                ->where('salidas.anio','=',$request->anio)->paginate(10);
            return [
            'pagination' => [
                'total'         => $query->total(),
                'current_page'  => $query->currentPage(),
                'per_page'      => $query->perPage(),
                'last_page'     => $query->lastPage(),
                'from'          => $query->firstItem(),
                'to'            => $query->lastItem(),
            ],
            'articulos' => $query
        ];
        }catch(Exception $e){

        }
    }
    public function store(Request $request)
    {
        try{
            $anioActual = now()->year; 
            $ultimoNumero = Salidas::where('anio', $anioActual)->max('numero_anual');
            foreach ($request->arrayPedido as $pedido) {
                $salida = new Salidas();
                $salida->fecha = now();
                $salida->cantidad = $pedido['cantidad'];
                $salida->numero_anual = $ultimoNumero ? $ultimoNumero + 1 : 1; 
                $salida->anio = $anioActual;
                $salida->id_articulo = $pedido['idarticulo'];
                $salida->id_personal = $request->idpersonal;
                $salida->save();

                $entradas = Entradas::select('id','restante','fecha','precio_unitario')->where('id_articulo','=',$pedido['idarticulo'])->where('restante', '>', 0)->where('anio', $anioActual)->orderBy('fecha','asc')->get();
                $satisfecho=$pedido['cantidad'];
                
                foreach ($entradas as $entrada){
                      if ($satisfecho <= 0) break;

                        $usar = min($entrada->restante, $satisfecho);
                        
                        $mov = new Movimientos();
                        $mov->id_salida = $salida->id;
                        $mov->id_entrada = $entrada->id;
                        $mov->cantidad_utilizada = $usar;
                        $mov->precio_unitario = $entrada->precio_unitario;
                        $mov->save();

                    $entrada->restante -= $usar;
                    $entrada->save();

                    $satisfecho -= $usar;
                }
                
            }
             return response()->json(['message' => 'Datos Guardados! ','numero_anual' => $ultimoNumero ? $ultimoNumero + 1 : 1, 'anio' => $anioActual], 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al registrar la entrada: ' . $e->getMessage()], 500);
        }
    }
   public function destroy($nro, $anio)
    {
        try {

            $salidas = Salidas::where('numero_anual', $nro)->where('anio', $anio)->get();

            foreach ($salidas as $item) {
                $movimientos = Movimientos::where('id_salida', $item->id)->get();

                foreach ($movimientos as $mov) {
                    $entrada = Entradas::findOrFail($mov->id_entrada);
                    $entrada->restante += $mov->cantidad_utilizada;
                    $entrada->save();

                    $mov->delete();
                }

                $item->delete();
            }
            return response()->json(['message' => 'Datos eliminados correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
    public function pdfsalida($fecha,$anio,$numeroanual){
        Date::setLocale('es');
        $date = new Date($fecha);
        $fechaTitulo = $date->format('l j F Y');
        $fechDerecha = $date->format('d/M/Y');
        $datos = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
            ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
            ->join('movimientos','salidas.id','=','movimientos.id_salida')
            ->select('movimientos.cantidad_utilizada as cantidad', 'articulos.codigo','unidades.nomunidad','articulos.descripcion' ,'movimientos.precio_unitario')
            ->where('salidas.numero_anual', $numeroanual)
            ->where('salidas.anio', $anio)
            ->orderBy('salidas.id', 'asc')->get();
        $resp = Salidas::join('personal','salidas.id_personal','=','personal.id')
                ->select('personal.nomper','personal.cargo')
                ->where('salidas.numero_anual','=',$numeroanual)
                ->where('salidas.anio','=',$anio)
                ->groupBy('personal.nomper')
                ->groupBy('personal.cargo')->first();
        $titulo = 'NOTA DE SALIDA N° '.$numeroanual.'/'.$anio;
        $subtitulo = 'CONSUMO / DISTRIBUCIÓN';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
        $filas = Salidas::join('movimientos','salidas.id','=','movimientos.id_salida')
                ->select('movimientos.cantidad_utilizada as cantidad','movimientos.precio_unitario')
                ->where('salidas.numero_anual', $numeroanual)->where('salidas.anio', $anio)->get();
        $resultados = [];

        foreach ($filas as $fila) {
            $multiplicacion = $fila->cantidad * $fila->precio_unitario; 
            $resultados[] = $multiplicacion;
        }

        $total = array_sum($resultados);
        
        $pdf=Pdf::loadView('plantillapdf.reportesalida',[
                'datos'=>$datos,
                'titulo'=>$titulo,
                'fechaTitulo'=>$fechaTitulo,
                'fechaDerecha'=>$fechDerecha,
                'almacen'=>$almacen->nomalmacen,
                'establecimiento'=>$establecimiento->nomestab,
                'ciudad'=>$ciudad->nomciudad,
                'subtitulo'=>$subtitulo,
                'total'=>$total,
                'resp'=>$resp,
                ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        }
    public function pdfSalidas(Request $request){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format(' j \\de F \\de Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $salidas = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
                            ->join('almacen','articulos.id_almacen','=','almacen.id')
                            ->join('personal', 'salidas.id_personal', '=', 'personal.id')
                            ->join('movimientos','salidas.id','=','movimientos.id_salida')
                            ->join('entradas','movimientos.id_entrada','=','entradas.id')
                            ->select(
                                'salidas.*',
                                'entradas.precio_unitario',
                                'articulos.codigo',
                                'articulos.descripcion',
                                'personal.nomper as personal',
                            )
                            ->where('salidas.anio','=',$request->gestion)
                            ->orderBy('salidas.numero_anual')
                            ->orderBy('salidas.id')
                            ->get()
                            ->groupBy('numero_anual');
        $datosAgrupados = [];

        foreach ($salidas as $numeroAnual => $grupo) {
            $subtotal = 0;
            foreach ($grupo as $item) {
                $subtotal += $item->cantidad * $item->precio_unitario;
            }

            $datosAgrupados[] = [
                'numero_anual' => $numeroAnual,
                'items' => $grupo,
                'subtotal' => $subtotal
            ];
        }

        $titulo = 'Listado de Pedidos';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
      
     $pdf=Pdf::loadView('plantillapdf.reportesalidas',[
            'datosAgrupados' => $datosAgrupados,
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
    public function exportarReporte(Request $request){
        $gestion=$request->gestion;
        return Excel::download(new SalidasExport($gestion), 'resumen.xlsx');
    }
}
