<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerReportes extends Controller
{
    public function reportes()
    {
        return view('reportes.index', [
            'pageTitle' => 'Reportes',
            'pageSubtitle' => 'Análisis y resúmenes de operaciones.',
            'pageDescription' => 'Genere reportes de actividad, rutas completadas y rendimiento de la flota.',
        ]);
    }
}
