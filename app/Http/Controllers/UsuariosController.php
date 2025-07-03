<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = usuarios::where('id', '!=', Auth::id())->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function edit($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = usuarios::findOrFail($id);

            $usuario->nombres = $request->nombres;
            $usuario->apellidos = $request->apellidos;
            $usuario->usuario = $request->usuario;
            $usuario->rol = $request->rol;
            $usuario->save();

            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo actualizar el usuario', 'error' => $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {
        Usuarios::destroy($id);
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
