<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    protected $table = "personal";
    protected $fillable = ['id','codper','nomper','ciper','telper','dirper','emailper','id_almacen'];
}
