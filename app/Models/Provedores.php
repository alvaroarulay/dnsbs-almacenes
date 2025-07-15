<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provedores extends Model
{
    use HasFactory;
    protected $table = "provedores";
    protected $fillable = ['id','nompro','direccion','telefono','nit','email','contacto','observaciones'];
}
 