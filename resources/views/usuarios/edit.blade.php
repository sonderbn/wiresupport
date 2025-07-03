@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Editar Usuario</h3>

    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombres" class="form-control" value="{{ $usuario->nombres }}" required>
        </div>

        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellidos" class="form-control" value="{{ $usuario->apellidos }}" required>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" value="{{ $usuario->usuario }}" required>
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control" required>
                <option value="1" {{ $usuario->rol == 1 ? 'selected' : '' }}>Usuario</option>
                <option value="0" {{ $usuario->rol == 0 ? 'selected' : '' }}>Técnico</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
