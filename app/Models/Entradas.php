<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entradas extends Model
{
    use HasFactory;
    protected $table = 'entradas';
    protected $fillable = [
        'fecha',
        'cantidad',
        'precio_unitario',
        'restante',
        'id_articulo',
        'id_personal', 
        'id_proveedor' 
    ];
}
