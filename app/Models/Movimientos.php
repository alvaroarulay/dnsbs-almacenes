<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use HasFactory;
    protected $table = 'movimientos';
    protected $fillable = [
        'cantidad_utilizada',
        'precio_unitario',
        'id_salida',
        'id_entrada'
    ];
}
