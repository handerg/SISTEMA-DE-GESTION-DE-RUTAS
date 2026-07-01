<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conductor;
use Illuminate\Support\Facades\Schema;
use App\Models\Auto;

class ControllerConductores extends Controller
{
    public function conductores(Request $request)
    {
        $query = Conductor::with('auto', 'historialRutas', 'historialServicios');

        if ($search = $request->query('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('nombre', 'like', "%{$search}%")
                    ->orWhere('cedula', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($tipoSangre = $request->query('tipo_sangre')) {
            $query->where('tipo_sangre', $tipoSangre);
        }

        if ($tipoLicencia = $request->query('tipo_licencia')) {
            $query->where('tipo_licencia', $tipoLicencia);
        }

        if ($autoId = $request->query('auto_id')) {
            $query->where('auto_id', $autoId);
        }

        $orderColumn = Schema::hasColumn('conductors', 'nombre') ? 'nombre' : 'id';

        return view('conductores.index', [
            'pageTitle' => 'Conductores',
            'pageSubtitle' => 'Gestión de los conductores activos en el sistema.',
            'pageDescription' => 'Revise el equipo de conducción, acceda a datos de contacto y controle las asignaciones actuales.',
            'conductores' => $query->orderBy($orderColumn)->get(),
            'autos' => Auto::all(),
            'search' => $search,
            'status' => $status,
            'tipo_sangre' => $tipoSangre,
            'tipo_licencia' => $tipoLicencia,
            'auto_id' => $autoId,
        ]);
    }

    public function storeConductor(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:50', 'unique:conductors,cedula'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'edad' => ['nullable', 'integer', 'min:18', 'max:100'],
            'sexo' => ['nullable', 'string', 'max:20'],
            'tipo_sangre' => ['nullable', 'string', 'max:10'],
            'tipo_licencia' => ['nullable', 'string', 'max:50'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
            'auto_id' => ['nullable', 'exists:autos,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('profile_photos', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        Conductor::create($data);

        return back()->with('success', 'Conductor creado correctamente.');
    }

    public function destroyConductor(Conductor $conductor)
    {
        $conductor->delete();

        return back()->with('success', 'Conductor eliminado correctamente.');
    }

    public function updateConductor(Request $request, Conductor $conductor)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:50', 'unique:conductors,cedula,' . $conductor->id],
            'telefono' => ['nullable', 'string', 'max:50'],
            'edad' => ['nullable', 'integer', 'min:18', 'max:100'],
            'sexo' => ['nullable', 'string', 'max:20'],
            'tipo_sangre' => ['nullable', 'string', 'max:10'],
            'tipo_licencia' => ['nullable', 'string', 'max:50'],
            'foto_perfil' => ['nullable', 'image', 'max:2048'],
            'auto_id' => ['nullable', 'exists:autos,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('profile_photos', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $conductor->update($data);

        return back()->with('success', 'Conductor actualizado correctamente.');
    }
}
