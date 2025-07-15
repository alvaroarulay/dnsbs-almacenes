<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Oficinas;

class OficinasController extends Controller
{
    public function index($id)
    {
        return response()->json(Oficinas::where('id_piso','=',$id)->get());
    }
    public function store(Request $request){
        try{
            $oficina = New Oficinas();
            $oficina->nomofic = $request->nomofic;
            $oficina->id_piso = $request->id_piso;
            $oficina->ubi_espec = $request->ubi_espec;
            $oficina->save();
            $id=$oficina->id;
            return response()->json(['message' => 'Datos Guardados Correctamente!!!','id'=>$id]); 
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request){
        try{
            $oficina = Oficinas::findOrFail($request->id);
            $oficina->nomofic = $request->nomofic;
            $oficina->ubi_espec = $request->ubi_espec;
            $oficina->save();
            $id=$oficina->id;
            return response()->json(['message' => 'Datos Guardados Correctamente!!!','id'=>$id]); 
        }catch (Exception $e){
            return response()->json(['message' => 'Escepción capturada: ' . $e->getMessage()]);
        }
    }
    public function destroy(Request $request){
        try{
            $oficina = Oficinas::findOrFail($request->id);
            $oficina->delete();
            return response()->json(['message' => 'Datos Guardados Correctamente!!!']); 
        }catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'foreign key constraint fails') !== false) {
                return response()->json(['message' => 'No se puede eliminar el oficina porque está siendo utilizado en otra tabla.']);
            }
            if ($e->getCode() == 1451) {
                return response()->json(['message' => 'No se puede eliminar el oficina porque está siendo utilizado en otra tabla.']);
            }
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' . $e->getMessage()]);
        }
    }
}
