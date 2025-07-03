<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Impresoras</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Reporte de Impresoras</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Sede</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($impresoras as $impresora)
                <tr>
                    <td>{{ $impresora->nombre_equipo }}</td>
                    <td>{{ $impresora->clinica->nombre ?? 'Sin sede' }}</td>
                    <td>{{ $impresora->modelo }}</td>
                    <td>{{ $impresora->numero_serie }}</td>
                    <td>{{ ucfirst($impresora->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>