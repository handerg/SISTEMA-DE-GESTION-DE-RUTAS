<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Traits\Authorizable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ControllerUsuarios extends Controller
{
    use Authorizable;
    
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
}
