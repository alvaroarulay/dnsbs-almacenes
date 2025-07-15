<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacenes extends Model
{
    use HasFactory;
    protected $table = "almacen";
    protected $fillable = ['id','nomalmacen','ubi_espec','id_establecimiento'];
}
