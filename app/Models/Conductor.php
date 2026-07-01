<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Auto;
use App\Models\HistorialRuta;
use App\Models\HistorialServicio;

class Conductor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'cedula',
        'telefono',
        'edad',
        'sexo',
        'tipo_sangre',
        'tipo_licencia',
        'foto_perfil',
        'auto_id',
        'is_active',
    ];

    public function auto()
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    public function historialRutas()
    {
        return $this->hasMany(HistorialRuta::class);
    }

    public function historialServicios()
    {
        return $this->hasMany(HistorialServicio::class);
    }
}
