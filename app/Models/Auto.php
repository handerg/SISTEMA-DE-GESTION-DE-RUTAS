<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'anio',
        'kilometraje',
        'color',
        'foto_perfil',
        'required_license',
    ];
}
