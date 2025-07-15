<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;

class EstablecimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return response()->json(Establecimiento::where('id_ciudad','=',$id)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return response()->json(['message'=>$request->nomestab,'direccion'=>$request->direccion,'telefono'=>$request->telefono,'id_ciudad'=>$request->id_ciudad]);
        try{
            $establecimiento = new Establecimiento;
            $establecimiento->nomestab = $request->nomestab;
            $establecimiento->direccion = $request->direccion;
            $establecimiento->telefono = $request->telefono;
            $establecimiento->id_ciudad = $request->id_ciudad;
            $establecimiento->save();
            return response()->json(['message'=>'registro exitoso','id_estab'=>$establecimiento->id]);
        }catch (Exception $e) {
            return response()->json(['message'=>$e]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            $establecimiento = Establecimiento::findOrFail($request->id);
            $establecimiento->nomestab = $request->nomestab;
            $establecimiento->direccion = $request->direccion;
            $establecimiento->telefono = $request->telefono;
            $establecimiento->save();
            return response()->json(['message'=>'registro exitoso','id_estab'=>$establecimiento->id]);
        }catch (Exception $e) {
            return response()->json(['message'=>$e]);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
