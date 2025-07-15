<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\Personal;
use App\Models\Almacenes;
use App\Models\Establecimiento;
use App\Models\Ciudad;
use App\Models\Provedores;
use App\Models\Articulos;

class EscritorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $entradas = Entradas::count();
      $salidas = Salidas::count();
      $personal = Personal::count();
      $almacenes = Almacenes::count();
      $provedores = Provedores::count();
      $articulos = Articulos::count();
      $users = User::count();
      return response()->json(['entradas'=>$entradas,
                                'salidas'=>$salidas,
                                'personal'=>$personal,    
                                'provedores'=>$provedores,
                                'almacenes'=>$almacenes,
                                'users'=>$users,
                                'articulos'=>$articulos,
                                ]);
    }
}
