<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Impresora</title>
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
    <h2>Ficha Técnica de la Impresora</h2>

    <div class="seccion">
        <p><span class="label">Nombre de la Impresora:</span> {{ $impresora->nombre_equipo }}</p>
        <p><span class="label">Sede:</span> {{ $impresora->clinica->nombre ?? 'Sin sede' }}</p>
        <p><span class="label">Modelo:</span> {{ $impresora->modelo }}</p>
        <p><span class="label">Número de Serie:</span> {{ $impresora->numero_serie }}</p>
    </div>

    <div class="seccion">
        <p><span class="label">Estado:</span> {{ ucfirst($impresora->estado) }}</p>
    </div>

    <div class="seccion" style="margin-top: 40px;">
        <p>Generado por el sistema <strong>WireSupport</strong> el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>