@extends('layouts.nav')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">Historial de Reparaciones - {{ $equipo->nombre_equipo }}</h3>

        <div class="mb-3">
            @php
                $ruta = $equipo->tipo === 'Impresora' ? 'impresoras.index' : 'equipos.index';
                $texto = $equipo->tipo === 'Impresora' ? 'Volver a Impresoras' : 'Volver a Equipos';
            @endphp
            <a href="{{ route($ruta) }}" class="btn btn-secondary">{{ $texto }}</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center table-sm">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-user"></i> Usuario</th>
                        <th><i class="fas fa-calendar-alt"></i> Creado el</th>
                        <th><i class="fas fa-info-circle"></i> Descripción</th>
                        <th><i class="fas fa-check-circle"></i> Solucionado el</th>
                        <th><i class="fas fa-comment-dots"></i> Descripción de la Solución</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ticketsTerminados as $ticket)
                        <tr>
                            <td>{{ $ticket->usuario->nombres ?? 'Sin usuario' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->fecha_creacion)->translatedFormat('d \d\e F \d\e\l Y \a \l\a\s H:i') }}
                            </td>
                            <td>{{ $ticket->descripcion }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->fecha_resolucion)->translatedFormat('d \d\e F \d\e\l Y \a \l\a\s H:i') }}
                            </td>
                            <td>{{ $ticket->solucion_descripcion ?? 'No disponible' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $ticketsTerminados->links() }}
        </div>
    </div>
@endsection
