<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    use HasFactory;
    protected $table='facturas';
    protected $fillable =[
        'id',
        'nro',
        'fecha',
        'codautorizacion',
        'codcontrol',
        'monto',
        'nro_anual',
        'gestion',
        'id_provedor'
    ];
}