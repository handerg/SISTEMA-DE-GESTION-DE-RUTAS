<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialRuta extends Model
{
    protected $fillable = [
        'conductor_id',
        'titulo',
        'detalle',
        'distancia',
    ];
}
