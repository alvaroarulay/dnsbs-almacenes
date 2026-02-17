<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\Personal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Ciudad;
use App\Models\Partidas;
use App\Models\Facturas;
use Jenssegers\Date\Date;

class EntradasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('almacen','articulos.id_almacen','=','almacen.id')
            ->join('personal', 'entradas.id_personal', '=', 'personal.id')
            ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
            ->leftJoin('documentos','entradas.id','=','producto_id')
            ->select('entradas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor','documentos.ruta')
            ->orderby('entradas.created_at','desc');
        if ($buscar=='') {
            $entradas = $query->paginate(10);
        } else {
            $entradas = $query->where('articulos.'.$criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $entradas->total(),
                'current_page'  => $entradas->currentPage(),
                'per_page'      => $entradas->perPage(),
                'last_page'     => $entradas->lastPage(),
                'from'          => $entradas->firstItem(),
                'to'            => $entradas->lastItem(),
            ],
            'entradas' => $entradas
        ];

    }
    public function notas(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query = Entradas::join('provedores','entradas.id_proveedor','=','provedores.id')
                ->join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                ->join('almacen','articulos.id_almacen','=','almacen.id')
                ->join('personal','entradas.id_personal','=','personal.id')
                ->select('entradas.numero_anual',
                'entradas.anio',
                'entradas.fecha',
                DB::raw('DATE(entradas.created_at) as created_at'),
                'personal.nomper',
                'provedores.nompro',
                DB::raw('COUNT(entradas.cantidad) as cantidad'),
                DB::raw('SUM(entradas.cantidad * entradas.precio_unitario) as total'),
                )->where('anio','=',$request->anio)
                ->where('saldo_inicial','=',false)
                ->groupBy('personal.nomper')
                ->groupBy('provedores.nompro')
                ->groupBy('entradas.fecha')
                ->groupBy('entradas.anio')
                ->groupBy(DB::raw('DATE(entradas.created_at)'))
                ->groupBy('entradas.numero_anual')
                ->orderBy('entradas.numero_anual','desc');
        if ($buscar=='') {
            $entradas = $query->paginate(10);
        } else {
            $entradas = $query->where($criterio, 'like', '%' . $buscar . '%')->paginate(10);
        }
        return [
            'pagination' => [
                'total'         => $entradas->total(),
                'current_page'  => $entradas->currentPage(),
                'per_page'      => $entradas->perPage(),
                'last_page'     => $entradas->lastPage(),
                'from'          => $entradas->firstItem(),
                'to'            => $entradas->lastItem(),
            ],
            'entradas' => $entradas
        ];
    }
    public function items(Request $request){
        try{
             $query = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                ->join('almacen','articulos.id_almacen','=','almacen.id')
                ->select('entradas.*','articulos.codigo','articulos.descripcion')
                ->where('entradas.numero_anual','=',$request->nro)
                ->where('entradas.anio','=',$request->anio)->paginate(10);
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
            $ultimoNumero = Entradas::where('anio', $anioActual)->max('numero_anual');
            foreach ($request->arrayCompras as $compra) {
                $entrada = new Entradas();
                $entrada->fecha = now();
                $entrada->cantidad = $compra['cantidad'];
                $entrada->precio_unitario = $compra['precio'];
                $entrada->restante = $compra['cantidad'];
                $entrada->id_articulo = $compra['idarticulo'];
                $entrada->anio = $anioActual;
                $entrada->numero_anual = $ultimoNumero ? $ultimoNumero + 1 : 1; 
                $entrada->id_personal = \Auth::id();
                $entrada->id_proveedor = $request->idprovedor;
                $entrada->save();
            }
            if($request->factura==1){
                
                $factura = new Facturas();
                $factura->nro = $request->nro;
                $factura->fecha = Carbon::parse($request->fechafac);
                $factura->codautorizacion = $request->autorizacion;
                $factura->codcontrol = $request->codcontrol;
                $factura->monto = $request->monto;
                $factura->nro_anual = $ultimoNumero ? $ultimoNumero + 1 : 1;
                $factura->gestion = $anioActual;
                $factura->id_provedor = $request->idprovedor;
                $factura->save();
            }
             return response()->json(['message' => 'Datos Guardados! ','numero_anual' => $entrada->numero_anual, 'anio' => $anioActual,'id'=>$entrada->id], 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al registrar la entrada: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request){
        try{
                $entrada = Entradas::findOrFail($request->id);
                $entrada->cantidad = $request->cantidad;
                $entrada->precio_unitario = $request->precio_unitario;
                $entrada->save();
             return response()->json(['message' => 'Datos Guardados! '], 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al registrar la entrada: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($nro,$anio){
        try{
            $entradas = Entradas::where('numero_anual','=',$nro)->where('anio','=',$anio)->get();
            foreach($entradas as $item){
                $entrada = Entradas::findOrFail($item->id);
                $entrada->delete();    
            }

            $idfactura = Facturas::select('id')->where('nro_anual','=',$nro)->where('gestion','=',$anio)->first(); 

            if($idfactura){
                $factura = Facturas::findOrFail($idfactura->id);
                $factura->delete();
            }
             return response()->json(['message' => 'Datos Guardados! '], 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al registrar la entrada: ' . $e->getMessage()], 500);
        }
    }
    public function pdfentrada($fecha, $anio, $numeroanual)
    {
        Date::setLocale('es');
            $date = new Date($fecha);
            $fechaTitulo = $date->format('l j F Y');
            $fechaDerecha = $date->format('d/M/Y');

        $partidas = Partidas::all();

        // ✅ Consulta corregida y optimizada
        $datos = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('personal', 'entradas.id_personal', '=', 'personal.id')
            ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
            ->select(
                'articulos.codigo',
                'articulos.id_partida',
                'articulos.descripcion',
                'personal.nomper as personal',
                'provedores.nompro as proveedor',
                'entradas.cantidad',
                'entradas.precio_unitario',
                DB::raw('(entradas.cantidad * entradas.precio_unitario) as subtotal')
            )
            ->where('entradas.numero_anual', $numeroanual)
            ->where('entradas.anio', $anio)
            ->orderBy('entradas.id', 'asc')
            ->groupBy(
                'articulos.id_partida',
                'articulos.codigo',
                'articulos.descripcion',
                'personal.nomper',
                'provedores.nompro',
                'entradas.cantidad',
                'entradas.precio_unitario',
                'entradas.id'
            )->groupBy('articulos.id_partida')
            ->get();
        $agrupado = $datos->groupBy('id_partida');


        // ✅ Factura
        $factura = Facturas::join('provedores', 'facturas.id_provedor', '=', 'provedores.id')
            ->select('facturas.*', 'provedores.nompro as razon', 'provedores.nit')
            ->where('nro_anual', $numeroanual)
            ->where('gestion', $anio)
            ->get();

        $subtitulo = $factura->isEmpty()
            ? 'COMPRA SIN FACTURA'
            : 'COMPRA CON FACTURA';

        // ✅ Datos de ubicación
        $almacen = Almacenes::where('seleccionado', 1)->first();
        $establecimiento = Establecimiento::find($almacen->id_establecimiento);
        $ciudad = Ciudad::find($establecimiento->id_ciudad);

        // ✅ Total general
        $total = $datos->sum('subtotal');

        // ✅ Subtotales por partida
        $subtotales = $datos
        ->groupBy('id_partida')
        ->map(function ($grupo) {
            return $grupo->sum('subtotal');
        });



        // ✅ Generación del PDF
        $pdf = Pdf::loadView('plantillapdf.reporteentrada', [
            'datos'          => $agrupado,
            'titulo'         => 'NOTA DE ENTRADA N° ' . $numeroanual . '/' . $anio,
            'fechaTitulo'    => $fechaTitulo,
            'fechaDerecha'   => $fechaDerecha,
            'almacen'        => $almacen->nomalmacen,
            'establecimiento'=> $establecimiento->nomestab,
            'ciudad'         => $ciudad->nomciudad,
            'subtitulo'      => $subtitulo,
            'total'          => $total,
            'factura'        => $factura,
            'partidas'       => $partidas,
            'subtotales'     => $subtotales,
        ]);

        $pdf->set_paper(array(0,0,612,936), 'portrait');

        return $pdf->stream();
    }
    public function pdfEntradas(Request $request){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format(' j \\de F \\de Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $entradas = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                            ->join('almacen','articulos.id_almacen','=','almacen.id')
                            ->join('personal', 'entradas.id_personal', '=', 'personal.id')
                            ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
                            ->select(
                                'entradas.*',
                                'articulos.codigo',
                                'articulos.descripcion',
                                'personal.nomper as personal',
                                'provedores.nompro as proveedor'
                            )
                            ->where('entradas.anio','=',$request->gestion)
                            ->where('entradas.saldo_inicial','=',false)
                            ->orderBy('entradas.numero_anual')
                            ->orderBy('entradas.id')
                            ->get()
                            ->groupBy('numero_anual');
        $datosAgrupados = [];

        foreach ($entradas as $numeroAnual => $grupo) {
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

        $titulo = 'Listado de Entradas';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
      
     $pdf=Pdf::loadView('plantillapdf.reporteentradas',[
            'datosAgrupados' => $datosAgrupados,
            'titulo'=>$titulo,
            'subtitulo'=>$fechaTitulo,
            'fechaDerecha'=>$fechDerecha,
            'almacen'=>$almacen->nomalmacen,
            'establecimiento'=>$establecimiento->nomestab,
            'ciudad'=>$ciudad->nomciudad,
            ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
    }
    public function grafica(Request $request){

        $ciudad=$request->ciudad;
        $establecimiento=$request->establecimiento;
        $almacen=$request->almacen;
        $pedido=$request->pedidos;
        $partida = $request->partida;
        
        try{
            if($request->fechai==null && $request->fechaf==null){
                $fechainicial = Carbon::parse('01-01-'.now()->year);
                $fechafinal = Carbon::parse('31-12-'.now()->year);
            }else{
                $fechainicial=Carbon::parse($request->fechai);
                $fechafinal=Carbon::parse($request->fechaf);
            }
            if($pedido=='compras'){
                $query = Entradas::join('articulos','articulos.id','=','entradas.id_articulo')
                                ->join('partidas','articulos.id_partida','=','partidas.id')
                                ->join('almacen','articulos.id_almacen','=','almacen.id')
                                ->join('establecimiento','almacen.id_establecimiento','=','establecimiento.id')
                                ->join('ciudad','establecimiento.id_ciudad','=','ciudad.id')
                ->select('partidas.codigo','partidas.nompartida',DB::raw('sum(entradas.cantidad * entradas.precio_unitario) as total'),DB::raw('sum(entradas.cantidad) as valor'))
                ->whereBetween(DB::raw('DATE(entradas.fecha)'), [$fechainicial, $fechafinal])
                ->groupBy('partidas.codigo', 'partidas.nompartida'); 
            }else{
                $query = Salidas::join('articulos','articulos.id','=','salidas.id_articulo')
                                ->join('partidas','articulos.id_partida','=','partidas.id')
                                ->join('almacen','articulos.id_almacen','=','almacen.id')
                                ->join('establecimiento','almacen.id_establecimiento','=','establecimiento.id')
                                ->join('ciudad','establecimiento.id_ciudad','=','ciudad.id')
                                ->join('movimientos','movimientos.id_salida','=','salidas.id')
                                ->join('entradas','movimientos.id_entrada','=','entradas.id')
                ->select('partidas.codigo','partidas.nompartida',DB::raw('sum(salidas.cantidad * entradas.precio_unitario) as total'),DB::raw('sum(salidas.cantidad) as valor'))
                ->whereBetween(DB::raw('DATE(salidas.fecha)'), [$fechainicial, $fechafinal])
                ->groupBy('partidas.codigo', 'partidas.nompartida');
            }
           
            if($ciudad==0){
              $partidas=$query->get();
            }else{
                if($establecimiento==0){
                    $partidas=$query->where('ciudad.id','=',$ciudad)->get();
                }else{
                    if($almacen==0){
                        $partidas=$query->where('ciudad.id','=',$ciudad)
                            ->where('establecimiento.id','=',$establecimiento)->get();
                    }else{
                        $partidas=$query->where('ciudad.id','=',$ciudad)
                            ->where('establecimiento.id','=',$establecimiento)
                            ->where('almacen.id','=',$almacen)->get();
                    }
                }
            }

            if($partida == 0){
                $partidas=$query->get();
            }else{
                $partidas=$query->where('partidas.id','=',$partida)->get();
            }
            return response()->json(['estados'=>$partidas]);  
        }catch(Exception $e){

        }
        
    }
    public function pdftotal(Request $request){
        try{
            Date::setLocale('es');
            $gestion = $request->gestion;
            $fechDerecha = Date::now()->format('d/M/Y');
            $almacen = Almacenes::where('seleccionado','=',1)->first();
            $actividad = '02 DIR. NAL. DE SALUD Y BIENESTAR SOCIAL';
            $financiamiento = 'TGN - 11 TGN OTROS';
            $titulo = 'INVENTARIO ACTUALIZADO';
            $fechaTitulo = date('d/m/') . $gestion;
            $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
            $partidas = Partidas::all();
            $query = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                                ->join('unidades', 'articulos.id_unidad', '=', 'unidades.id')
                                ->select(
                                    'articulos.id as id_articulo',
                                    'articulos.codigo as codigo_articulo',
                                    'articulos.descripcion as descripcion_articulo',
                                    'articulos.id_partida as id_partida',
                                    'unidades.nomunidad as unidad_medida',
                                    DB::raw('sum(entradas.restante) as stock_final'),
                                    DB::raw('AVG(entradas.precio_unitario) as precio_unitario'),
                                    DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final')
                                )
                                ->where('entradas.anio', '=', $gestion)
                                ->where('entradas.restante', '!=', 0)
                                ->groupBy(
                                    'articulos.id',
                                    'articulos.codigo',
                                    'unidades.nomunidad',
                                    'articulos.descripcion'
                                )->orderBy('articulos.codigo');
            $resultado = $query->get()->groupBy('id_partida');
            $subtotales = $resultado->map(function ($items) {
                    return [
                        'total_final' => $items->sum('total_final'),
                    ];
                });
            $total = [
                'total_final' => collect($query->get())->sum('total_final'),
            ];


            $pdf=Pdf::loadView('plantillapdf.reportetotal',[
                'partidas'=>$partidas,
                'datos'=>$resultado,
                'titulo'=>$titulo,
                'fechaTitulo'=>$fechaTitulo,
                'fechaDerecha'=>$fechDerecha,
                'almacen'=>$almacen,
                'establecimiento'=>$establecimiento,
                'actividad'=>$actividad,
                'financiamiento'=>$financiamiento,
                'subtotales' => $subtotales,
                'total' => $total['total_final'],
                ]);
        $pdf->set_paper(array(0,0,612,936), 'portrait');
        return $pdf->stream();
        }catch(Exception $e){

        }
        
    }
    public function gestiones(){
        try{
            $gestiones = Entradas::select('anio as gestion')->distinct()->orderBy('anio', 'desc')->get();
            return response()->json($gestiones);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al obtener las gestiones: ' . $e->getMessage()], 500);
        }
    }
    public function resumengestion(Request $request){
        try{
           $gestion = $request->gestion;
           $entrada = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
            ->select(
                'partidas.codigo',
                'partidas.nompartida',
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad ELSE 0 END) as stock_inicial'),
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = false THEN entradas.cantidad ELSE 0 END) as compras'),
                //salidas
                DB::raw('sum(entradas.restante) as stock_final'),
                DB::raw('sum(CASE WHEN entradas.saldo_inicial = true THEN entradas.cantidad * entradas.precio_unitario ELSE 0 END) as total_inicio'),
                DB::raw('sum(entradas.restante * entradas.precio_unitario) as total_final'))


            ->where('entradas.anio','=', $gestion)
            ->groupBy('partidas.codigo', 'partidas.nompartida')->orderby('partidas.codigo', 'asc');

            $salida = Salidas::join('articulos', 'salidas.id_articulo', '=', 'articulos.id')
            ->join('partidas', 'articulos.id_partida', '=', 'partidas.id')
            ->select(
                'partidas.codigo',
                'partidas.nompartida',
                //DB::raw('sum(salidas.cantidad * salidas.precio_unitario) as total_salidas'), 
                
                DB::raw('sum(salidas.cantidad) as cantidad_salidas'))
            ->where('salidas.anio','=', $gestion)
            ->groupBy('partidas.codigo', 'partidas.nompartida')->orderby('partidas.codigo', 'asc');

                // Obtener los resultados como arrays
            $entradas = $entrada->get()->keyBy('codigo')->toArray();
            $salidas = $salida->get()->keyBy('codigo')->toArray();

            $partidas = [];

            foreach ($entradas as $codigo => $entrada) {
                $salida = $salidas[$codigo] ?? ['cantidad_salidas' => 0];
               
                $partidas[] = [
                    'codigo'         => $codigo,
                    'nompartida'     => $entrada['nompartida'],
                    'stock_inicial'  => $entrada['stock_inicial'],
                    'compras'        => $entrada['compras'],
                    'pedidos'        => $salida['cantidad_salidas'], // ← este es "Pedidos"
                    'stock_final'    => $entrada['stock_final'],
                    'total_inicio'   => $entrada['total_inicio'],
                    'total_final'    => $entrada['total_final'],
                ];
            }
            return response()->json($partidas);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al obtener el resumen de la gestión: ' . $e->getMessage()], 500);
        }
    }

}