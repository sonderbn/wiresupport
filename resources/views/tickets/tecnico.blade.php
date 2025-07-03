@extends('layouts.nav')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-3">Tickets Asignados</h5>
        </div>

        <h6 class="mb-2">Filtrar Tickets Pendientes</h6>
        <div class="mb-3">
            <form method="GET" action="{{ route('tickets.tecnico') }}">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="usuario_nombre" id="filtroUsuarioPendiente" class="form-control"
                            placeholder="Buscar por usuario" value="{{ request('usuario_nombre') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="id_clinica" class="form-control">
                            <option value="">Seleccionar Sede</option>
                            @foreach ($clinicas as $clinica)
                                <option value="{{ $clinica->Id_clinica }}"
                                    {{ request('id_clinica') == $clinica->Id_clinica ? 'selected' : '' }}>
                                    {{ $clinica->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="tipo_equipo" class="form-control">
                            <option value="">Seleccionar Tipo de Equipo</option>
                            <option value="PC" {{ request('tipo_equipo') == 'PC' ? 'selected' : '' }}>PC</option>
                            <option value="Impresora" {{ request('tipo_equipo') == 'Impresora' ? 'selected' : '' }}>
                                Impresora</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="descripcion" class="form-control">
                            <option value="">Seleccionar Descripción</option>
                            <option value="La computadora no enciende"
                                {{ request('descripcion') == 'La computadora no enciende' ? 'selected' : '' }}>La
                                computadora no enciende</option>
                            <option value="La pantalla está en negro"
                                {{ request('descripcion') == 'La pantalla está en negro' ? 'selected' : '' }}>La pantalla
                                está en negro</option>
                            <option value="Problemas de conexión a Internet"
                                {{ request('descripcion') == 'Problemas de conexión a Internet' ? 'selected' : '' }}>
                                Problemas de conexión a Internet</option>
                            <option value="El software no se abre"
                                {{ request('descripcion') == 'El software no se abre' ? 'selected' : '' }}>El software no
                                se abre</option>
                            <option value="La impresora no imprime"
                                {{ request('descripcion') == 'La impresora no imprime' ? 'selected' : '' }}>La impresora no
                                imprime</option>
                            <option value="Se acabó el toner"
                                {{ request('descripcion') == 'Se acabó el toner' ? 'selected' : '' }}>Se acabó el toner
                            </option>
                            <option value="No hay tinta negra"
                                {{ request('descripcion') == 'No hay tinta negra' ? 'selected' : '' }}>No hay tinta negra
                            </option>
                            <option value="No hay tinta azul"
                                {{ request('descripcion') == 'No hay tinta azul' ? 'selected' : '' }}>No hay tinta azul
                            </option>
                            <option value="No hay tinta roja"
                                {{ request('descripcion') == 'No hay tinta roja' ? 'selected' : '' }}>No hay tinta roja
                            </option>
                            <option value="No hay tinta amarilla"
                                {{ request('descripcion') == 'No hay tinta amarilla' ? 'selected' : '' }}>No hay tinta
                                amarilla</option>
                            <option value="El teclado no responde"
                                {{ request('descripcion') == 'El teclado no responde' ? 'selected' : '' }}>El teclado no
                                responde</option>
                            <option value="Problemas con el sistema operativo"
                                {{ request('descripcion') == 'Problemas con el sistema operativo' ? 'selected' : '' }}>
                                Problemas con el sistema operativo</option>
                            <option value="El mouse no funciona"
                                {{ request('descripcion') == 'El mouse no funciona' ? 'selected' : '' }}>El mouse no
                                funciona</option>
                            <option value="La computadora se reinicia sola"
                                {{ request('descripcion') == 'La computadora se reinicia sola' ? 'selected' : '' }}>La
                                computadora se reinicia sola</option>
                            <option value="Otro" {{ request('descripcion') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="fecha_creacion" class="form-control"
                            value="{{ request('fecha_creacion') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" name="filtrar_recientes" class="form-check-input" id="filtrarRecientes"
                                {{ request('filtrar_recientes') ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtrarRecientes">Filtrar recientes</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
                        <a href="{{ route('tickets.tecnico') }}" class="btn btn-secondary mt-2">Limpiar Filtros</a>
                    </div>
                </div>
            </form>
        </div>

        <h6 class="mb-2">Tickets Pendientes</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsPendientes as $ticket)
                @include('tickets.partials.card_tecnico', ['ticket' => $ticket])
            @endforeach
        </div>
        {{ $ticketsPendientes->links('components.custom-pagination', ['pageName' => 'pendientes_page']) }}

        <h6 class="mb-2 mt-5">Filtrar Tickets Terminados</h6>
        <div class="mb-3">
            <form method="GET" action="{{ route('tickets.tecnico') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="usuario_nombre_terminado" id="filtroUsuarioTerminado"
                            class="form-control" placeholder="Buscar por usuario"
                            value="{{ request('usuario_nombre_terminado') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="tipo_equipo_terminado" class="form-control">
                            <option value="">Seleccionar Tipo de Equipo</option>
                            <option value="PC" {{ request('tipo_equipo_terminado') == 'PC' ? 'selected' : '' }}>PC
                            </option>
                            <option value="Impresora"
                                {{ request('tipo_equipo_terminado') == 'Impresora' ? 'selected' : '' }}>
                                Impresora</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="descripcion_terminado" class="form-control">
                            <option value="">Seleccionar Descripción</option>
                            <option value="La computadora no enciende"
                                {{ request('descripcion_terminado') == 'La computadora no enciende' ? 'selected' : '' }}>La
                                computadora no enciende</option>
                            <option value="La pantalla está en negro"
                                {{ request('descripcion_terminado') == 'La pantalla está en negro' ? 'selected' : '' }}>La pantalla
                                está en negro</option>
                            <option value="Problemas de conexión a Internet"
                                {{ request('descripcion_terminado') == 'Problemas de conexión a Internet' ? 'selected' : '' }}>
                                Problemas de conexión a Internet</option>
                            <option value="El software no se abre"
                                {{ request('descripcion_terminado') == 'El software no se abre' ? 'selected' : '' }}>El software no
                                se abre</option>
                            <option value="La impresora no imprime"
                                {{ request('descripcion_terminado') == 'La impresora no imprime' ? 'selected' : '' }}>La impresora no
                                imprime</option>
                            <option value="Se acabó el toner"
                                {{ request('descripcion_terminado') == 'Se acabó el toner' ? 'selected' : '' }}>Se acabó el toner
                            </option>
                            <option value="No hay tinta negra"
                                {{ request('descripcion_terminado') == 'No hay tinta negra' ? 'selected' : '' }}>No hay tinta negra
                            </option>
                            <option value="No hay tinta azul"
                                {{ request('descripcion_terminado') == 'No hay tinta azul' ? 'selected' : '' }}>No hay tinta azul
                            </option>
                            <option value="No hay tinta roja"
                                {{ request('descripcion_terminado') == 'No hay tinta roja' ? 'selected' : '' }}>No hay tinta roja
                            </option>
                            <option value="No hay tinta amarilla"
                                {{ request('descripcion_terminado') == 'No hay tinta amarilla' ? 'selected' : '' }}>No hay tinta
                                amarilla</option>
                            <option value="El teclado no responde"
                                {{ request('descripcion_terminado') == 'El teclado no responde' ? 'selected' : '' }}>El teclado no
                                responde</option>
                            <option value="Problemas con el sistema operativo"
                                {{ request('descripcion_terminado') == 'Problemas con el sistema operativo' ? 'selected' : '' }}>
                                Problemas con el sistema operativo</option>
                            <option value="El mouse no funciona"
                                {{ request('descripcion_terminado') == 'El mouse no funciona' ? 'selected' : '' }}>El mouse no
                                funciona</option>
                            <option value="La computadora se reinicia sola"
                                {{ request('descripcion_terminado') == 'La computadora se reinicia sola' ? 'selected' : '' }}>La
                                computadora se reinicia sola</option>
                            <option value="Otro" {{ request('descripcion_terminado') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            <option value="Otro" {{ request('descripcion_terminado') == 'Otro' ? 'selected' : '' }}>Mi
                                problema no aparece aquí</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="fecha_resolucion" class="form-control"
                            value="{{ request('fecha_resolucion') }}">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="filtrar_recientes_terminados" class="form-check-input"
                                id="filtrarRecientesTerminados"
                                {{ request('filtrar_recientes_terminados') ? 'checked' : '' }}>
                            <label class="form-check-label" for="filtrarRecientesTerminados">Filtrar recientes</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
                        <a href="{{ route('tickets.tecnico') }}" class="btn btn-secondary mt-2">Limpiar Filtros</a>
                    </div>
                </div>
            </form>
        </div>

        <h6 class="mb-2">Tickets Terminados</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
            @foreach ($ticketsTerminados as $ticket)
                @include('tickets.partials.card_tecnico', ['ticket' => $ticket])
            @endforeach
        </div>
        {{ $ticketsTerminados->links('components.custom-pagination', ['pageName' => 'terminados_page']) }}
    </div>

    @include('tickets.partials.modal_tecnico')
@endsection

@push('scripts')
    <script>
        function verTicketTecnico(ticketId) {
            fetch(`/tickets/${ticketId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.estado === 'terminado') {
                        alert('Este ticket ya está terminado y no se puede editar.');
                        return;
                    }

                    document.getElementById('modalClinica').textContent = data.clinica.nombre;
                    document.getElementById('modalEquipo').textContent = data.equipo.nombre_equipo;
                    document.getElementById('modalFechaCreacion').textContent = data.fecha_creacion;
                    document.getElementById('modalDescripcion').textContent = data.descripcion;

                    const ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));
                    ticketModal.show();

                    document.getElementById('btnMarcarSolucionado').onclick = function() {
                        marcarComoSolucionado(ticketId);
                    };
                });
        }

        function marcarComoSolucionado(ticketId) {
            const solucionDescripcion = document.getElementById('solucionDescripcion').value;

            fetch(`/tickets/${ticketId}/solucionar`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        estado: 'terminado',
                        fecha_resolucion: new Date().toISOString(),
                        solucion_descripcion: solucionDescripcion
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta del servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Ticket y equipo actualizados');
                        location.reload();
                    } else {
                        alert(data.error || 'Error desconocido');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert('Error al conectar con el servidor');
                });
        }
    </script>
@endpush
