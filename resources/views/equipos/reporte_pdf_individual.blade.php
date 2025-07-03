<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte del Equipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 14px;
            color: #333;
        }
        h2 {
            text-align: center;
            text-transform: uppercase;
        }
        .seccion {
            margin-bottom: 20px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Ficha Técnica del Equipo</h2>

    <div class="seccion">
        <p><span class="label">Nombre del Equipo:</span> {{ $equipo->nombre_equipo }}</p>
        <p><span class="label">Sede:</span> {{ $equipo->clinica->nombre ?? 'Sin sede' }}</p>
        <p><span class="label">Modelo:</span> {{ $equipo->modelo }}</p>
        <p><span class="label">Número de Serie:</span> {{ $equipo->numero_serie }}</p>
    </div>

    <div class="seccion">
        <p><span class="label">Procesador:</span> {{ $equipo->procesador }}</p>
        <p><span class="label">Memoria RAM:</span> {{ $equipo->memoria_ram }}</p>
        <p><span class="label">Disco Duro:</span> {{ $equipo->disco_duro }}</p>
    </div>

    <div class="seccion">
        <p><span class="label">Tipo de Equipo:</span> {{ ucfirst($equipo->tipo) }}</p>
        <p><span class="label">Estado:</span> {{ ucfirst($equipo->estado) }}</p>
    </div>

    <div class="seccion" style="margin-top: 40px;">
        <p>Generado por el sistema <strong>WireSupport</strong> el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
