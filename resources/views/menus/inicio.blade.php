@extends('layouts.nav')

@section('content')
@php
    $nombreCompleto = Auth::user()->nombres ?? 'Usuario';
    $nombresArray = explode(' ', $nombreCompleto);
    $nombreMostrar = implode(' ', array_slice($nombresArray, 0, 2));
@endphp
    <div class="container mt-4">
        <h3 class="mb-4">Panel</h3>

        {{-- Cuadros superiores --}}
        <div class="row text-white">
            <div class="col-md-4">
                <div class="card bg-warning shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-ticket-alt fa-3x"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Tickets Pendientes</h6>
                            <h2 class="card-text">{{ $ticketsPendientes }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card bg-primary shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-desktop fa-3x"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Equipos Registrados</h6>
                            <h2 class="card-text">{{ $equiposRegistrados }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card bg-success shadow rounded-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                        <div>
                            <h6 class="card-title mb-1">Usuarios Registrados</h6>
                            <h2 class="card-text">{{ $usuariosRegistrados }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="row mt-5 text-center">
    <div class="col-12">
        <h1 style="overflow: hidden; text-overflow: ellipsis;">
            ¡Hola,{{ $nombreMostrar }}! Bienvenido al gestor de tickets de
            <span class="text-primary">WireSupport</span>
        </h1>
        <p>Aqui tienes un resumen de tus tickets pendientes y los equipos registrados de cada sede</p>
    </div>
</div>

        {{-- Gráficos --}}
        <div class="row mt-5">
            <div class="col-md-6 text-center">
                <h5>Tickets Pendientes por Sede</h5>
                <div style="max-width: 300px; margin: auto;">
                    <canvas id="graficoTickets"></canvas>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h5>Equipos por Sede</h5>
                <div style="max-width: 300px; margin: auto;">
                    <canvas id="graficoEquipos"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        const ticketsClinica = @json($ticketsPorClinica);
        const equiposClinica = @json($equiposPorClinica);

        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
        new Chart(document.getElementById('graficoTickets').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ticketsClinica.map(item => item.clinica),
                datasets: [{
                    label: 'Tickets pendientes',
                    data: ticketsClinica.map(item => item.total),
                    backgroundColor: ticketsClinica.map(() => getRandomColor()),
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        color: '#000',
                        anchor: 'center',
                        align: 'center',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: value => value
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                responsive: true
            }
        });

        new Chart(document.getElementById('graficoEquipos').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: equiposClinica.map(item => item.clinica),
                datasets: [{
                    label: 'Equipos registrados',
                    data: equiposClinica.map(item => item.total),
                    backgroundColor: equiposClinica.map(() => getRandomColor()),
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        color: '#000',
                        anchor: 'center',
                        align: 'center',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: value => value
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                responsive: true
            }
        });
    </script>
@endsection
