<div class="col">
    <div class="position-relative">
        <div class="card h-100 shadow-sm ticket-card {{ $ticket->estado === 'pendiente' ? 'pendiente' : 'terminado' }}"
            onclick="verTicketTecnico({{ $ticket->Id_tickets }})"
            style="cursor:pointer; border-radius: 10px; margin-top: 15px; margin-bottom: 15px; position: relative;">

            <span
                class="position-absolute badge {{ $ticket->estado === 'pendiente' ? 'bg-warning text-dark' : 'bg-success' }}"
                style="left: -5px; top: -10px; z-index: 10; box-shadow: 0 0 5px rgba(0,0,0,0.2);">
                {{ ucfirst($ticket->estado) }}
            </span>

            @php
                $sedeColor = match ($ticket->clinica->nombre ?? '') {
                    'Trujillo' => 'bg-primary',
                    'Huaraz' => 'bg-danger',
                    'Piura' => 'bg-info',
                    'Lima' => 'bg-secondary',
                    default => 'bg-dark'
                };
            @endphp
            <span class="position-absolute badge {{ $sedeColor }}"
                style="right: -2px; top: -10px; z-index: 10; box-shadow: 0 0 5px rgba(0,0,0,0.2);">
                {{ $ticket->clinica->nombre ?? 'Sin sede' }}
            </span>

            <i class="fas fa-tools" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; font-size: 100px; z-index: 1;"></i>

            <div class="card-body" style="position: relative; z-index: 2;">
                <h6 class="card-title">
                    @if ($ticket->equipo->tipo === 'PC')
                        <i class="fas fa-desktop" style="margin-right: 5px;"></i>
                    @elseif ($ticket->equipo->tipo === 'Impresora')
                        <i class="fas fa-print" style="margin-right: 5px;"></i>
                    @else
                        <i class="fas fa-question-circle" style="margin-right: 5px;"></i>
                    @endif
                    {{ $ticket->equipo->nombre_equipo ?? 'Equipo no asignado' }}
                </h6>
                <p class="mb-1">
                    <i class="fas fa-user" style="margin-right: 5px;"></i>
                    <strong>Usuario:</strong> {{ $ticket->usuario->nombres ?? 'Sin usuario' }}
                </p>
                <p class="mb-1">
                    <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                    <strong>Creado el:</strong>
                    {{ \Carbon\Carbon::parse($ticket->fecha_creacion)->translatedFormat('d \d\e F \d\e\l Y \a \l\a\s H:i') }}
                </p>
                <p class="text-muted">
                    <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                    <strong>Descripción:</strong> {{ $ticket->descripcion }}
                </p>

                @if ($ticket->estado === 'terminado')
                    <p class="text-muted mb-0">
                        <i class="fas fa-check-circle" style="margin-right: 5px;"></i>
                        <strong>Solucionado el:</strong>
                        {{ \Carbon\Carbon::parse($ticket->fecha_resolucion)->translatedFormat('d \d\e F \d\e\l Y \a \l\a\s H:i') }}
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-comment-dots" style="margin-right: 5px;"></i>
                        <strong>Descripción de la Solución:</strong> {{ $ticket->solucion_descripcion ?? 'No disponible' }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .ticket-card {
        transition: all 0.2s ease-in-out;
        background-color: white !important;
        position: relative;
    }

    .ticket-card.pendiente:hover {
        background-color: #ffc107 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .ticket-card.terminado:hover {
        background-color: #198754 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .ticket-card:hover * {
        color: white !important;
    }

    .ticket-card:hover .text-muted {
        color: #e0e0e0 !important;
    }

    .position-absolute.badge {
        min-width: 70px;
        text-align: center;
    }

    .card-body i {
        color: #6c757d;
    }
</style>