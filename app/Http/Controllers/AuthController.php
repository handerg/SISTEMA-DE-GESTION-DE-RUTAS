<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\Authorizable;

class AuthController extends Controller
{
    use Authorizable;

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

    public function dashboard()
    {
        return view('dashboard.index', [
            'pageTitle' => 'Panel principal',
            'pageSubtitle' => 'Bienvenido al sistema Avante. Desde aquí puedes acceder rápidamente a la gestión de autos, conductores y reportes.',
        ]);
    }
}
