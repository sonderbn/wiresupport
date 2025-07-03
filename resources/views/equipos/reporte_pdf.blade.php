<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Equipos</title>
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
    <h2>Reporte de Equipos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Sede</th>
                <th>Modelo</th>
                <th>Serie</th>
                <th>Procesador</th>
                <th>RAM</th>
                <th>Disco Duro</th>
                <th>Tipo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->nombre_equipo }}</td>
                    <td>{{ $equipo->clinica->nombre ?? 'Sin sede' }}</td>
                    <td>{{ $equipo->modelo }}</td>
                    <td>{{ $equipo->numero_serie }}</td>
                    <td>{{ $equipo->procesador }}</td>
                    <td>{{ $equipo->memoria_ram }}</td>
                    <td>{{ $equipo->disco_duro }}</td>
                    <td>{{ $equipo->tipo }}</td>
                    <td>{{ ucfirst($equipo->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>