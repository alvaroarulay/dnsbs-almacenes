<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad;

class CiudadesController extends Controller
{
    public function index(){
        return response()->json(Ciudad::all());
    }
}
