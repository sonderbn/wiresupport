@extends('layouts.nav')

@section('content')
    <div class="container mt-4 position-relative">
        <h3 class="mb-4">Gestión de Usuarios</h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="table-primary">
                    <tr>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-user-tag"></i> Apellido</th>
                        <th><i class="fas fa-at"></i> Usuario</th>
                        <th><i class="fas fa-user-shield"></i> Rol</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr id="fila-{{ $usuario->id }}">
                            <td class="td-nombres">{{ $usuario->nombres }}</td>
                            <td class="td-apellidos">{{ $usuario->apellidos }}</td>
                            <td class="td-usuario">{{ $usuario->usuario }}</td>
                            <td class="td-rol">
                                @if ($usuario->rol == 0)
                                    <i class="fas fa-tools"></i> Técnico
                                @elseif ($usuario->rol == 1)
                                    <i class="fas fa-user"></i> Usuario
                                @else
                                    <i class="fas fa-question-circle"></i> Desconocido
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editarUsuarioModal" data-id="{{ $usuario->id }}"
                                    data-nombres="{{ $usuario->nombres }}" data-apellidos="{{ $usuario->apellidos }}"
                                    data-usuario="{{ $usuario->usuario }}" data-rol="{{ $usuario->rol }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditarUsuario">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nombres" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="edit-nombres" name="nombres" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="edit-apellidos" name="apellidos" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="edit-usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-rol" class="form-label">Rol</label>
                            <select class="form-control" id="edit-rol" name="rol">
                                <option value="0">Técnico</option>
                                <option value="1">Usuario</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JS para manejar el modal y AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editarUsuarioModal');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('edit-id').value = button.getAttribute('data-id');
                document.getElementById('edit-nombres').value = button.getAttribute('data-nombres');
                document.getElementById('edit-apellidos').value = button.getAttribute('data-apellidos');
                document.getElementById('edit-usuario').value = button.getAttribute('data-usuario');
                document.getElementById('edit-rol').value = button.getAttribute('data-rol');
            });

            document.getElementById('formEditarUsuario').addEventListener('submit', function(e) {
                e.preventDefault();
                const id = document.getElementById('edit-id').value;
                const formData = new FormData(this);

                fetch(`/usuarios/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Error en la solicitud.");
                        return response.json();
                    })
                    .then(data => {
                        // Actualiza la fila con nuevos valores
                        const fila = document.getElementById(`fila-${id}`);
                        fila.querySelector('.td-nombres').textContent = formData.get('nombres');
                        fila.querySelector('.td-apellidos').textContent = formData.get('apellidos');
                        fila.querySelector('.td-usuario').textContent = formData.get('usuario');
                        fila.querySelector('.td-rol').textContent = formData.get('rol');

                        // Cierra el modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'editarUsuarioModal'));
                        modal.hide();

                        // Forzar quitar el backdrop y la clase que bloquea scroll
                        document.body.classList.remove('modal-open');
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    })
                    .catch(error => alert("No se pudo actualizar el usuario"));
            });
        });
    </script>

    <style>
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            font-size: 200px;
            color: #000;
            z-index: 0;
            pointer-events: none;
            font-family: "Font Awesome 5 Free";
        }

        .table {
            font-size: 0.9rem; /* Tamaño de fuente más pequeño para la tabla */
        }

        .table th, .table td {
            vertical-align: middle; /* Centrar verticalmente el contenido */
        }
    </style>
@endsection