<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Models\Conductor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'El usuario no existe en la base de datos.'])
                ->onlyInput('email');
        }

        if (! $user->is_active) {
            return back()
                ->withErrors(['email' => 'El usuario está bloqueado. Contacta al administrador.'])
                ->onlyInput('email');
        }

        $roleName = $user->role?->nombre_rol;
        if (! in_array($roleName, ['administrador', 'supervisor'], true)) {
            return back()
                ->withErrors(['email' => 'No tienes permiso para acceder al sistema.'])
                ->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function authorizeAdmin()
    {
        if (! auth()->user()?->isAdmin()) {
            abort(403);
        }
    }

    public function dashboard()
    {
        return view('dashboard.index', [
            'pageTitle' => 'Panel principal',
            'pageSubtitle' => 'Bienvenido al sistema Avante. Desde aquí puedes acceder rápidamente a la gestión de autos, conductores y reportes.',
        ]);
    }

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

    public function usuarios()
    {
        $this->authorizeAdmin();

        return view('usuarios.index', [
            'pageTitle' => 'Usuarios',
            'pageSubtitle' => 'Administración de cuentas de usuario.',
            'pageDescription' => 'Agrega, bloquea o elimina usuarios del sistema.',
            'users' => User::with('role')->get(),
        ]);
    }

    public function storeUsuario(Request $request)
    {
        $this->authorizeAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role_id' => ['required', 'exists:roles,id_rol'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'is_active' => true,
        ]);

        return redirect()->route('usuarios')->with('success', 'Usuario creado correctamente.');
    }

    public function toggleUsuario(User $user)
    {
        $this->authorizeAdmin();

        $user->is_active = ! $user->is_active;
        $user->save();

        return redirect()->route('usuarios')->with('success', 'Estado de usuario actualizado.');
    }

    public function destroyUsuario(User $user)
    {
        $this->authorizeAdmin();

        $user->delete();

        return redirect()->route('usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    public function reportes()
    {
        return view('reportes.index', [
            'pageTitle' => 'Reportes',
            'pageSubtitle' => 'Análisis y resúmenes de operaciones.',
            'pageDescription' => 'Genere reportes de actividad, rutas completadas y rendimiento de la flota.',
        ]);
    }

    public function configuracion()
    {
        return view('configuracion.index', [
            'pageTitle' => 'Configuración',
            'pageSubtitle' => 'Ajustes del sistema y preferencias.',
            'pageDescription' => 'Modifique parámetros del sistema, datos de la empresa y opciones de usuario.',
        ]);
    }
}
