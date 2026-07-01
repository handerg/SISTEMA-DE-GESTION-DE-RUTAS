<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auto;

class ControllerAutos extends Controller
{
    public function autos()
    {
        return view('autos.index', [
            'pageTitle' => 'Autos',
            'pageSubtitle' => 'Gestión de la flota de vehículos registrados.',
            'pageDescription' => 'Administre los autos disponibles, revise el estado de cada unidad y gestione el mantenimiento de la flota.',
            'autos' => Auto::all(),
        ]);
    }

    public function storeAuto(Request $request)
    {
        $data = $request->validate([
            'placa' => ['required', 'string', 'max:50'],
            'marca' => ['required', 'string', 'max:100'],
            'modelo' => ['required', 'string', 'max:100'],
            'anio' => ['required', 'digits:4'],
            'kilometraje' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'required_license' => ['nullable', 'string', 'max:20'],
            'foto_perfil' => ['required', 'image', 'max:2048'],
        ]);

        $data['foto_perfil'] = $request->file('foto_perfil')->store('profile_photos', 'public');

        Auto::create($data);

        return back()->with('success', 'Auto creado correctamente.');
    }
}
