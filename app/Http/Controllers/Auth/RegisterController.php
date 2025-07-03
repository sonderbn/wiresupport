<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuarios;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.registrar');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'usuario' => 'required|string|max:30|unique:usuarios,usuario',
            'password' => 'required|string|max:20|confirmed',
        ]);

        $user = Usuarios::create([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
            'rol' => 1,
        ]);

        auth()->guard('usuarios')->login($user);

        return redirect()->route('login');
    }
}
