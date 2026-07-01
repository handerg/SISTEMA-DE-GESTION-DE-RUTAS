<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerConfiguracion extends Controller
{
    public function configuracion()
    {
        return view('configuracion.index', [
            'pageTitle' => 'Configuración',
            'pageSubtitle' => 'Ajustes del sistema y preferencias.',
            'pageDescription' => 'Modifique parámetros del sistema, datos de la empresa y opciones de usuario.',
        ]);
    }
}
