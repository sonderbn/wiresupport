@extends('layouts.nav')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">Gestión de Equipos - Computadoras</h3>
        <form method="GET" action="{{ route('equipos.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre, modelo o serie"
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
                <button class="btn btn-primary btn-sm me-2">Filtrar</button>

                @if (request('buscar') || request('sede'))
                    <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary btn-sm me-2">Borrar filtros</a>
                @endif

                <button type="button" class="btn btn-success btn-sm ms-auto" data-bs-toggle="modal"
                    data-bs-target="#modalAgregar">
                    <i class="fas fa-plus"></i> Añadir Equipo
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center table-sm">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-desktop"></i> Nombre</th>
                        <th><i class="fas fa-hospital-alt"></i> Sede</th>
                        <th><i class="fas fa-cogs"></i> Modelo</th>
                        <th><i class="fas fa-barcode"></i> Serie</th>
                        <th><i class="fas fa-microchip"></i> Procesador</th>
                        <th><i class="fas fa-memory"></i> RAM</th>
                        <th><i class="fas fa-hdd"></i> Disco Duro</th>
                        <th><i class="fas fa-check-circle"></i> Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipos as $equipo)
                        <tr>
                            <td style="font-size: 0.8rem;">{{ $equipo->nombre_equipo }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->clinica->nombre ?? 'Sin sede' }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->modelo }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->numero_serie }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->procesador }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->memoria_ram }}</td>
                            <td style="font-size: 0.8rem;">{{ $equipo->disco_duro }}</td>
                            <td>
                                @if ($equipo->estado === 'activo')
                                    <span class="estado-chip activo">Activo</span>
                                @elseif($equipo->estado === 'en reparacion')
                                    <span class="estado-chip en-reparacion">En reparación</span>
                                @else
                                    <span class="estado-chip inactivo">{{ $equipo->estado }}</span>
                                @endif
                            </td>
                            <td style="min-width: 300px;">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                    data-bs-target="#modalEditar{{ $equipo->Id_equipo }}"
                                    onclick="event.stopPropagation()">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <form class="d-inline-block me-1"
                                    action="{{ route('equipos.destroy', $equipo->Id_equipo) }}" method="POST"
                                    onsubmit="return confirm('¿Seguro desea eliminar este equipo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="event.stopPropagation()">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>

                                <a href="{{ route('equipos.historialReparaciones', $equipo->Id_equipo) }}"
                                    class="btn btn-sm btn-info" onclick="event.stopPropagation()">
                                    <i class="fas fa-history"></i> Ver historial de reparaciones
                                </a>

                                <a href="{{ route('equipos.reportePdfIndividual', $equipo->Id_equipo) }}"
                                    class="btn btn-danger btn-sm" title="Generar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('equipos.exportarExcelIndividual', $equipo->Id_equipo) }}"
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
                <a href="{{ route('equipos.reportePdf', ['sede' => request('sede'), 'buscar' => request('buscar')]) }}"
                    class="btn btn-danger btn-sm me-2">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
                <a href="{{ route('equipos.reporteExcel', ['sede' => request('sede'), 'buscar' => request('buscar')]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Generar Excel
                </a>
            </div>

            <nav>
                <ul class="pagination">
                    <li class="page-item {{ $equipos->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $equipos->previousPageUrl() }}">«</a>
                    </li>
                    @foreach ($equipos->getUrlRange(1, $equipos->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $equipos->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $equipos->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $equipos->nextPageUrl() }}">»</a>
                    </li>
                </ul>
            </nav>
        </div>

        @foreach ($equipos as $equipo)
            <div class="modal fade" id="modalEditar{{ $equipo->Id_equipo }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('equipos.update', $equipo->Id_equipo) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Equipo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            @include('equipos.form', ['equipo' => $equipo])
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Actualizar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <div class="modal fade" id="modalAgregar" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form id="formAgregarEquipo" action="{{ route('equipos.store') }}" method="POST"
                    class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Añadir Equipo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        @include('equipos.form', ['equipo' => null])
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <style>
            .estado-chip {
                display: inline-block;
                padding: 6px 12px;
                font-size: 0.75rem;
                font-weight: 500;
                border-radius: 20px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
                transition: background-color 0.3s, box-shadow 0.3s;
                color: white;
            }

            .estado-chip.activo {
                background-color: #28a745;
            }

            .estado-chip.en-reparacion {
                background-color: #ff9800;
                color: black;
            }

            .estado-chip.inactivo {
                background-color: #6c757d;
            }

            .pagination .page-link {
                border: none;
                border-radius: 50px;
                margin: 0 4px;
                padding: 6px 12px;
                color: #333;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease-in-out;
            }

            .pagination .page-link:hover {
                background-color: #e0e0e0;
            }

            .pagination .page-item.active .page-link {
                background-color: #1976d2;
                color: white;
                font-weight: bold;
            }

            .tr-hover:hover {
                background-color: rgb(211, 209, 209) !important;
                cursor: pointer;
            }

            .table-responsive {
                position: relative;
            }

            .table-responsive::before {
                content: '';
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
                content: "\f1c5";
            }

            table {
                font-size: 0.8rem;
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
                const modales = document.querySelectorAll('[id^="modalEditar"]');

                modales.forEach(modalEl => {
                    modalEl.addEventListener('hidden.bs.modal', function() {
                        const form = modalEl.querySelector('form');
                        if (form) form.reset();
                    });
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                const formAgregarEquipo = document.getElementById('formAgregarEquipo');

                formAgregarEquipo.addEventListener('submit', function(event) {                    event.preventDefault();

                    const numeroSerieInput = formAgregarEquipo.querySelector('input[name="numero_serie"]');
                    const numeroSerie = numeroSerieInput.value;

                    fetch(`/equipos/check-numero-serie?numero_serie=${numeroSerie}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                numeroSerieInput.classList.add('is-invalid');
                                numeroSerieInput.nextElementSibling.textContent =
                                    'El número de serie ya está registrado.';
                            } else {
                                formAgregarEquipo.submit();
                            }
                        });
                });
            });
        </script>
    </div>
@endsection
