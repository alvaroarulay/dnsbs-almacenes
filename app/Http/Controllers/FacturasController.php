<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturas;

class FacturasController extends Controller
{
    public function index(Request $request){
        try{
            $numeroanual = $request->numeroanual;
            $anio = $request->anio;
            $factura=Facturas::join('provedores','facturas.id_provedor','=','provedores.id')
                        ->select('facturas.*','provedores.nompro as razon','provedores.nit')
                        ->where('nro_anual','=',$numeroanual)->where('gestion','=',$anio)->first();
            return response()->json($factura);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al obtener las facturas: ' . $e->getMessage()], 500);
        }
    }
    public function store(Request $request){
        try{
            $factura=new Facturas();
            $factura->nro=$request->nro;
            $factura->fecha=$request->fecha;
            $factura->codautorizacion=$request->codautorizacion;
            $factura->codcontrol=$request->codcontrol;
            $factura->monto=$request->monto;
            $factura->nro_anual=$request->nro_anual;
            $factura->gestion=$request->gestion;
            $factura->id_provedor=$request->id_provedor;
            $factura->save();
            return response()->json(['message' => 'Factura registrada exitosamente'], 201);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al registrar la factura: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request){
        try{
            $factura=Facturas::find($request->id);
            $factura->nro=$request->nro;
            $factura->fecha=$request->fecha;
            $factura->codautorizacion=$request->codautorizacion;
            $factura->codcontrol=$request->codcontrol;
            $factura->monto=$request->monto;
            $factura->nro_anual=$request->nro_anual;
            $factura->gestion=$request->gestion;
            $factura->id_provedor=$request->id_provedor;
            $factura->save();
            return response()->json(['message' => 'Factura actualizada exitosamente'], 200);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al actualizar la factura: ' . $e->getMessage()], 500); 
        }
    }
}
