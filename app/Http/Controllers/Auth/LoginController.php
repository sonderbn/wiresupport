<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|max:30',
            'password' => 'required|max:20',
        ]);
        $credentials = $request->only('usuario', 'password');

        $user = \App\Models\Usuarios::where('usuario', $credentials['usuario'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::guard('usuarios')->login($user);
            return redirect()->intended('/inicio');
        }

        return back()->withErrors([
            'usuario' => 'Credenciales incorrectas.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
