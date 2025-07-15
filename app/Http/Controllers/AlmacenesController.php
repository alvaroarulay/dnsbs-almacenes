<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Almacenes;

class AlmacenesController extends Controller
{
    public function titulo(){
        return response()->json(Almacenes::where('seleccionado','=',1)->first());
    }
    public function index($id)
    {
        return response()->json(Almacenes::where('id_establecimiento','=',$id)->get());
    }
    public function store(Request $request)
    {
        try{
            $almacen = new Almacenes;
            $almacen->nomalmacen = $request->nomalmacen;
            $almacen->ubi_espec = $request->ubiespec;
            $almacen->id_establecimiento = $request->id_establecimiento;
            $almacen->save();
            return response()->json(['message'=>'Datos guardados correctamente!']);
        }catch (Exception $e) {
            return response()->json(['message'=>$e]);
         }
    }
     public function update(Request $request)
    {
        try{
            
            $almacen = Almacenes::findOrFail($request->id);
            $almacen->nomalmacen = $request->nomalmacen;
            $almacen->ubi_espec = $request->ubiespec;
            $almacen->save();
            return response()->json(['message'=>'Datos Actualizados correctamente!']);
        }catch (Exception $e) {
            return response()->json(['message'=>$e]);
         }
    }
    public function select($id){
        //return response()->json(['message'=>'Almacen Seleccionado!'.$id]);
       Almacenes::where('seleccionado','=',1)->update(['seleccionado' => 0]);
        try{
            $almacen = Almacenes::findOrFail($id);
            $almacen->seleccionado = 1;
            $almacen->save();
            return response()->json(['message'=>'Almacen Seleccionado!']);
        }catch (Exception $e) {
            return response()->json(['message'=>$e]);
         }
    }

}
