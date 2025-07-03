@extends('layouts.nav')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">Gestión de Impresoras</h3>

        <form method="GET" action="{{ route('impresoras.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o serie"
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-4">
                <select name="sede" class="form-select">
                    <option value="">-- Filtrar por sede --</option>
                    @foreach ($clinicas as $clinica)
                        <option value="{{ $clinica->Id_clinica }}"
                            {{ request('sede') == $clinica->Id_clinica ? 'selected' : '' }}>
                            {{ $clinica->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex justify-content-between align-items-start">
                <button class="btn btn-primary me-2">Filtrar</button>

                @if (request('buscar') || request('sede'))
                    <a href="{{ route('impresoras.index') }}" class="btn btn-outline-secondary me-2">Borrar filtros</a>
                @endif

                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal"
                    data-bs-target="#modalAgregarImpresora">
                    <i class="fas fa-plus"></i> Añadir Impresora
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center table-sm">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-print"></i> Nombre</th>
                        <th><i class="fas fa-hospital-alt"></i> Sede</th>
                        <th><i class="fas fa-cogs"></i> Modelo</th>
                        <th><i class="fas fa-barcode"></i> Serie</th>
                        <th><i class="fas fa-check-circle"></i> Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($impresoras as $impresora)
                        <tr>
                            <td>{{ $impresora->nombre_equipo }}</td>
                            <td>{{ $impresora->clinica->nombre ?? 'Sin sede' }}</td>
                            <td>{{ $impresora->modelo }}</td>
                            <td>{{ $impresora->numero_serie }}</td>
                            <td>
                                @if ($impresora->estado === 'activo')
                                    <span class="estado-chip activo">Activo</span>
                                @elseif($impresora->estado === 'en reparacion')
                                    <span class="estado-chip en-reparacion">En reparación</span>
                                @else
                                    <span class="estado-chip inactivo">{{ $impresora->estado }}</span>
                                @endif
                            </td>
                            <td style="min-width: 300px;">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                    data-bs-target="#modalEditarImpresora{{ $impresora->Id_equipo }}"
                                    onclick="event.stopPropagation()">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <form class="d-inline-block me-1"
                                    action="{{ route('equipos.destroy', $impresora->Id_equipo) }}" method="POST"
                                    onsubmit="return confirm('¿Seguro de eliminar esta impresora?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="event.stopPropagation()">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>

                                <a href="{{ route('equipos.historialReparaciones', $impresora->Id_equipo) }}"
                                    class="btn btn-sm btn-info" onclick="event.stopPropagation()">
                                    <i class="fas fa-history"></i> Ver historial de reparaciones
                                </a>

                                <a href="{{ route('impresoras.reportePdfIndividual', $impresora->Id_equipo) }}"
                                    class="btn btn-danger btn-sm" title="Generar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('impresoras.exportarExcelIndividual', $impresora->Id_equipo) }}"
                                    class="btn btn-success btn-sm" title="Generar Excel">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <div>
                <a href="{{ route('impresoras.reportePdf', ['sede' => request('sede'), 'buscar' => request('buscar')]) }}"
                    class="btn btn-danger btn-sm me-2">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
                <a href="{{ route('impresoras.reporteExcel', ['sede' => request('sede'), 'buscar' => request('buscar')]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Generar Excel
                </a>
            </div>

            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $impresoras->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $impresoras->previousPageUrl() }}">«</a>
                    </li>
                    @foreach ($impresoras->getUrlRange(1, $impresoras->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $impresoras->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $impresoras->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $impresoras->nextPageUrl() }}">»</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="modal fade" id="modalAgregarImpresora" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form id="formAgregarImpresora" action="{{ route('equipos.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Añadir Impresora</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-12">
                            <label>Nombre</label>
                            <input type="text" name="nombre_equipo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Modelo</label>
                            <input type="text" name="modelo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Número de serie</label>
                            <input type="text" name="numero_serie" class="form-control" required>
                            @error('numero_serie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Sede</label>
                            <select name="Id_clinica" class="form-select" required>
                                @foreach ($clinicas as $clinica)
                                    <option value="{{ $clinica->Id_clinica }}">{{ $clinica->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="en reparacion">En reparación</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <input type="hidden" name="tipo" value="Impresora">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($impresoras as $impresora)
            <div class="modal fade" id="modalEditarImpresora{{ $impresora->Id_equipo }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('equipos.update', $impresora->Id_equipo) }}" method="POST"
                        class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Impresora</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-12">
                                <label>Nombre</label>
                                <input type="text" name="nombre_equipo" class="form-control"
                                    value="{{ $impresora->nombre_equipo }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Modelo</label>
                                <input type="text" name="modelo" class="form-control"
                                    value="{{ $impresora->modelo }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Número de serie</label>
                                <input type="text" name="numero_serie" class="form-control"
                                    value="{{ $impresora->numero_serie }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Sede</label>
                                <select name="Id_clinica" class="form-select" required>
                                    @foreach ($clinicas as $clinica)
                                        <option value="{{ $clinica->Id_clinica }}"
                                            {{ $impresora->Id_clinica == $clinica->Id_clinica ? 'selected' : '' }}>
                                            {{ $clinica->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="activo" {{ $impresora->estado == 'activo' ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="en reparacion"
                                        {{ $impresora->estado == 'en reparacion' ? 'selected' : '' }}>En reparación
                                    </option>
                                    <option value="inactivo" {{ $impresora->estado == 'inactivo' ? 'selected' : '' }}>
                                        Inactivo</option>
                                </select>
                            </div>
                            <input type="hidden" name="tipo" value="Impresora">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Actualizar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .estado-chip {
            display: inline-block;
            padding: 6px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            transition: all 0.2s;
        }

        .estado-chip.activo {
            background-color: #28a745;
            color: white;
        }

        .estado-chip.en-reparacion {
            background-color: #ffc107;
            color: black;
        }

        .estado-chip.inactivo {
            background-color: #6c757d;
            color: white;
        }

        td .btn {
            margin-right: 4px;
            position: relative;

            z-index: 1;
        }

        tr[data-bs-target] .btn {
            position: relative;
            z-index: 10;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formAgregarImpresora = document.getElementById('formAgregarImpresora');

            formAgregarImpresora.addEventListener('submit', function(event) {
                event.preventDefault();

                const numeroSerieInput = formAgregarImpresora.querySelector('input[name="numero_serie"]');
                const numeroSerie = numeroSerieInput.value;

                fetch(`/equipos/check-numero-serie?numero_serie=${numeroSerie}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            numeroSerieInput.classList.add('is-invalid');
                            numeroSerieInput.nextElementSibling.textContent =
                                'El número de serie ya está registrado.';
                        } else {
                            formAgregarImpresora.submit();
                        }
                    });
            });
        });
    </script>
@endsection
