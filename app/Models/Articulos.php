<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulos extends Model
{
    use HasFactory;
    protected $table = "articulos";
    protected $fillable = ['id','cod_anterior','codigo','descripcion','id_unidad','id_almacen','id_partida'];
}
