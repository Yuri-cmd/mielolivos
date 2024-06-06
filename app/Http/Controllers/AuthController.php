<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('usuario_id')) {
            $rol = Session::get('rol');
            if ($rol == 1) {
                return redirect()->route('admin.dashboard');
            } elseif ($rol == 2) {
                return redirect()->route('vendedor.dashboard');
            } else {
                return redirect()->route('cobrador.dashboard');
            }
        } else {
            return view('auth.login');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required'],
            'password' => ['required'],
        ]);

        // Buscar el usuario por el nombre de usuario
        $user = Usuario::where('usuario', $credentials['usuario'])->first();

        // Verificar si se encontró el usuario y si la contraseña coincide
        if ($user && $credentials['password'] == $user->password && $user->estado == 1) {
            // Autenticación exitosa
            $request->session()->regenerate();
            $request->session()->put('usuario_id', $user->id);
            $request->session()->put('usuario', $user->usuario);
            $request->session()->put('rol', $user->rol);

            // Redirigir al usuario según su rol
            if ($user->rol == 1) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->rol == 2) {
                return redirect()->intended(route('vendedor.dashboard'));
            } else {
                return redirect()->intended(route('cobrador.dashboard'));
            }
        } else {
            // Autenticación fallida: mostrar mensaje de error
            $errorMessage = __('auth.failed');
            return redirect()->back()->withErrors(['usuario' => $errorMessage]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
