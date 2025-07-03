@extends('layouts.nav') {{-- Asegúrate de que este extiende tu layout con sidebar --}}
@php
    \Carbon\Carbon::setLocale('es');
@endphp
@section('content')
<div class="container py-4">
    {{-- Sección de Tickets --}}
    <h5 class="mb-3">Mis Tickets</h5>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
        @forelse ($tickets as $ticket)
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 border {{ $ticket->estado == 'pendiente' ? 'border-warning' : 'border-success' }}" style="min-height: 160px;">
                        <div class="card-body d-flex align-items-center">
                            {{-- Ícono --}}
                            <div class="me-3 fs-1">
                                @if ($ticket->estado === 'pendiente')
                                    <i class="fas fa-hammer text-warning"></i>
                                @else
                                    <i class="fas fa-circle-check text-success"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="card-title mb-1">
                                    {{ $ticket->equipo->nombre_equipo ?? 'Sin equipo' }}
                                </h6>
                                <p class="card-text mb-0"><strong>{{ $ticket->clinica->nombre ?? 'Sin clínica' }}</strong></p>
                                <p class="card-text">
                                    {{ \Carbon\Carbon::parse($ticket->fecha_creacion)->translatedFormat('d \d\e F \d\e\l Y') }}
                                </p>
                                <span class="badge {{ $ticket->estado == 'pendiente' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ ucfirst($ticket->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-muted">No tienes tickets registrados.</p>
        @endforelse
    </div>
    {{-- Paginación --}}
    <div class="mt-3">
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->onEachSide(1)->links('components.custom-pagination') }}
        </div>
    </div>

    {{-- Mensaje de bienvenida --}}
    <div class="card mt-4 border-success">
        <div class="card-body text-center">
            <h5 class="mb-3">¡Hola {{ $user->nombres }}! Bienvenido a <span class="text-success fw-bold">WireSupport</span></h5>
            <p class="mb-2">Revisa el estado de tus tickets activos y mantente al tanto de cualquier novedad.<br>
            Si tienes alguna duda o problema, ¡solo avísanos!</p>
            <p class="fw-semibold mt-3">
                Actualmente, tienes {{ str_pad($tickets->where('estado', 'pendiente')->count(), 2, '0', STR_PAD_LEFT) }} tickets pendientes,<br>
                los técnicos atenderán lo más pronto posible tu caso.
            </p>
        </div>
    </div>
</div>
@endsection
