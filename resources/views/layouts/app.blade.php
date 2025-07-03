<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Outsourcing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        .container-fluid {
            padding: 0; /* Eliminar padding */
            margin: 0; /* Eliminar margen */
        }
        body {
            margin: 0; /* Asegurarse de que el body no tenga margen */
        }
    </style>
</head>

<body>
    <div class="container-fluid"> <!-- Usar container-fluid -->
        @yield('content')
    </div>

    <!-- Solo bootstrap.bundle.min.js (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>

</body>

</html>
