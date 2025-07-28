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
            ->select('entradas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor')
            ->where('almacen.seleccionado','=',1);
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
                ->select('entradas.numero_anual','entradas.anio','entradas.fecha','entradas.created_at','personal.nomper','provedores.nompro')
                ->where('almacen.seleccionado','=',1)
                ->groupBy('personal.nomper')
                ->groupBy('provedores.nompro')
                ->groupBy('entradas.fecha')
                ->groupBy('entradas.anio')
                ->groupBy('entradas.created_at')
                ->groupBy('entradas.numero_anual')
                ->orderBy('entradas.created_at','desc');
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
                $entrada->id_personal = $request->idpersonal;
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
             return response()->json(['message' => 'Datos Guardados! ','numero_anual' => $entrada->numero_anual, 'anio' => $anioActual], 200);
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
    public function pdfentrada($fecha,$anio,$numeroanual){
        Date::setLocale('es');
        $date = new Date($fecha);
        $fechaTitulo = $date->format('l j F Y');
        $fechDerecha = $date->format('d/M/Y');
        $datos = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('personal', 'entradas.id_personal', '=', 'personal.id')
            ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
            ->select('entradas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor')
            ->where('entradas.numero_anual', $numeroanual)
            ->where('entradas.anio', $anio)
            ->orderBy('entradas.id', 'asc')->get();
        $factura=Facturas::join('provedores','facturas.id_provedor','=','provedores.id')
                            ->select('facturas.*','provedores.nompro as razon','provedores.nit')
                            ->where('nro_anual','=',$numeroanual)->where('gestion','=',$anio)->get();
        if($factura->count()==0){
           $subtitulo='COMPRA SIN FACTURA'; 
        }else{
            $subtitulo='COMPRA CON FACTURA';
        }
        
        $titulo = 'NOTA DE ENTRADA N° '.$numeroanual.'/'.$anio;
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
        $filas = Entradas::where('entradas.numero_anual', $numeroanual)->where('entradas.anio', $anio)->get();
        $resultados = [];

        foreach ($filas as $fila) {
            $multiplicacion = $fila->cantidad * $fila->precio_unitario; 
            $resultados[] = $multiplicacion;
        }

        $total = array_sum($resultados);
        
        $pdf=Pdf::loadView('plantillapdf.reporteentradas',[
                'datos'=>$datos,
                'titulo'=>$titulo,
                'fechaTitulo'=>$fechaTitulo,
                'fechaDerecha'=>$fechDerecha,
                'almacen'=>$almacen->nomalmacen,
                'establecimiento'=>$establecimiento->nomestab,
                'ciudad'=>$ciudad->nomciudad,
                'subtitulo'=>$subtitulo,
                'total'=>$total,
                'factura'=>$factura,
                ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        }
    public function pdfEntradas(){
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format(' j \\de F \\de Y');
        $fechDerecha = Date::now()->format('d/M/Y');
        $datos = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
            ->join('almacen','articulos.id_almacen','=','almacen.id')
            ->join('personal', 'entradas.id_personal', '=', 'personal.id')
            ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
            ->select('entradas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor')
            ->where('almacen.seleccionado','=',1)
            ->orderBy('entradas.id', 'asc')->get();
        $filas = Entradas::join('articulos', 'entradas.id_articulo', '=', 'articulos.id')
                ->join('almacen','articulos.id_almacen','=','almacen.id')
                ->select('entradas.*')->where('almacen.seleccionado','=',1)->get();
        $resultados = [];

        foreach ($filas as $fila) {
            $multiplicacion = $fila->cantidad * $fila->precio_unitario; 
            $resultados[] = $multiplicacion;
        }
        $factura = Facturas::where('codcontrol','=',0)->get();
        $total = array_sum($resultados);
        $titulo = 'Listado de Compras';
        $almacen = Almacenes::where('seleccionado','=',1)->first();
        $establecimiento = Establecimiento::where('id','=',$almacen->id_establecimiento)->first();
        $ciudad = Ciudad::where('id','=',$establecimiento->id_ciudad)->first();
      
     $pdf=Pdf::loadView('plantillapdf.reporteentradas',[
            'datos'=>$datos,
            'titulo'=>$titulo,
            'subtitulo'=>$fechaTitulo,
            'fechaDerecha'=>$fechDerecha,
            'almacen'=>$almacen->nomalmacen,
            'establecimiento'=>$establecimiento->nomestab,
            'ciudad'=>$ciudad->nomciudad,
            'total'=>$total,
            'factura'=>$factura,
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
                ->select('partidas.codigo','partidas.nompartida',DB::raw('count(*) as valor'))
                ->whereBetween(DB::raw('DATE(salidas.fecha)'), [$fechainicial, $fechafinal])
                ->groupBy('partidas.codigo'); 
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

        Date::setLocale('es');
       
        $fechDerecha = Date::now()->format('d/M/Y');
        $idciudad=$request->ciudad;
        $idestablecimiento=$request->establecimiento;
        $idalmacen=$request->almacen;
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
                $titulo = 'LISTADO DE COMPRAS';
                $query = Entradas::join('articulos','articulos.id','=','entradas.id_articulo')
                                ->join('partidas','articulos.id_partida','=','partidas.id')
                                ->join('almacen','articulos.id_almacen','=','almacen.id')
                                ->join('establecimiento','almacen.id_establecimiento','=','establecimiento.id')
                                ->join('ciudad','establecimiento.id_ciudad','=','ciudad.id')
                                ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
                                ->join('personal', 'entradas.id_personal', '=', 'personal.id')
                ->select('entradas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor')
                ->whereBetween(DB::raw('DATE(entradas.fecha)'), [$fechainicial, $fechafinal]);
            }else{
                $titulo = 'LISTADO DE PEDIDOS';
                $query = Salidas::join('articulos','articulos.id','=','salidas.id_articulo')
                                ->leftjoin('movimientos','movimientos.id_salida','=','salidas.id')
                                ->leftjoin('entradas','movimientos.id_entrada','=','entradas.id')
                                ->join('partidas','articulos.id_partida','=','partidas.id')
                                ->join('almacen','articulos.id_almacen','=','almacen.id')
                                ->join('establecimiento','almacen.id_establecimiento','=','establecimiento.id')
                                ->join('ciudad','establecimiento.id_ciudad','=','ciudad.id')
                                ->join('provedores', 'entradas.id_proveedor', '=', 'provedores.id')
                                ->join('personal', 'entradas.id_personal', '=', 'personal.id')
                ->select('salidas.*', 'articulos.codigo','articulos.descripcion' , 'personal.nomper as personal', 'provedores.nompro as proveedor','entradas.precio_unitario')
                ->whereBetween(DB::raw('DATE(salidas.fecha)'), [$fechainicial, $fechafinal]); 
            }
        
            if($idciudad==0){
                $ciudad = 'LA PAZ';
                $establecimiento = 'TODOS LOS ESTABLECIMIENTOS';
                $almacen = 'TODOS LOS ALMACENES';
                $partidas=$query->get();
            }else{
                if($idestablecimiento==0){
                    $cc = Ciudad::where('id','=',$idciudad)->first();
                    $ciudad = $cc->nomciudad;
                    $establecimiento = 'ESTABLECIMIENTOS DE LA CIUDAD DE '. $ciudad;
                    $almacen = 'TODOS LOS ALMACENES';
                    $partidas=$query->where('ciudad.id','=',$idciudad)->get();
                }else{
                    if($idalmacen==0){
                        $cc = Ciudad::where('id','=',$idciudad)->first();
                        $ciudad = $cc->nomciudad;
                        $ee = Establecimiento::where('id','=',$idestablecimiento)->first();
                        $establecimiento = $ee->nomestab;
                        $almacen = 'TODOS LOS ALMACENES';
                        $partidas=$query->where('ciudad.id','=',$idciudad)
                            ->where('establecimiento.id','=',$idestablecimiento)->get();
                    }else{
                        $cc = Ciudad::where('id','=',$idciudad)->first();
                        $ciudad = $cc->nomciudad;
                        $ee = Establecimiento::where('id','=',$idestablecimiento)->first();
                        $establecimiento = $ee->nomestab;
                        $aa = Almacenes::where('id','=',$idalmacen)->first();
                        $almacen = $aa->nomalmacen;
                        $partidas=$query->where('ciudad.id','=',$idciudad)
                            ->where('establecimiento.id','=',$idestablecimiento)
                            ->where('almacen.id','=',$idalmacen)->get();
                    }
                }
            }

            if($partida == 0){
                $partidas=$query->get();
            }else{
                $partidas=$query->where('partidas.id','=',$partida)->get();
            }
            $resultados = [];

            foreach ($query->get() as $fila) {
                $multiplicacion = $fila->cantidad * $fila->precio_unitario; 
                $resultados[] = $multiplicacion;
            }
            $total = array_sum($resultados);
            $fecin=new Date($fechainicial);
            $fecfn = new Date($fechafinal);
            $fechaTitulo = 'Del '.$fecin->format('d/m/Y') .' al '.$fecfn->format('d/m/Y');
            $pdf=Pdf::loadView('plantillapdf.reportetotal',[
                'datos'=>$partidas,
                'titulo'=>$titulo,
                'fechaTitulo'=>$fechaTitulo,
                'fechaDerecha'=>$fechDerecha,
                'almacen'=>$almacen,
                'establecimiento'=>$establecimiento,
                'ciudad'=>$ciudad,
                'subtitulo'=>'',
                'total'=>$total,
                ]);
        $pdf->set_paper('letter', 'portrait');
        return $pdf->stream();
        }catch(Exception $e){

        }
        
    }
}