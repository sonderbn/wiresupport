@php
    $user = auth()->guard('usuarios')->user();
@endphp
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WireSupport</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .nav-link {
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.2s ease-in-out;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: black !important;
        }

        .nav-link:hover i {
            color: black;
        }

        .active-link {
            background-color: white !important;
            color: #000 !important;
            font-weight: bold;
        }

        .active-link i {
            color: #000 !important;
        }

        .active-submenu-link {
            background-color: rgba(255, 255, 255, 0.85) !important;
            color: #000 !important;
            font-weight: 500;
        }

        .submenu {
            display: none; 
            padding-left: 0;
            list-style: none; 
            margin-top: 5px;
        }

        .submenu.show {
            display: block;
            animation: fadeIn 0.3s;
        }

        .nav-item .nav-link.has-submenu::after {
            content: '\f078';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
            padding-left: 10px;
            font-size: 0.8rem;
            transition: transform 0.3s;
        }

        .nav-item .nav-link.has-submenu.active::after {
            transform: rotate(180deg);
        }

        .submenu .nav-link {
            padding: 6px 12px 6px 36px;
            margin: 2px 0;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .submenu .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: black !important;
        }

        .submenu .nav-link.active-submenu-link {
            background-color: white !important;
            color: #000 !important;
        }

        .submenu .nav-link.active-submenu-link:hover {
            background-color: white !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="text-white p-3 d-flex flex-column position-fixed"
            style="width: 250px; height: 100vh; background-color: #5F9B41;">
            <h4>WireSupport</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ Request::routeIs('menus.inicio') ? 'active-link' : 'text-white' }}"
                        href="{{ route('menus.inicio') }}">
                        <i class="fas fa-home me-2 {{ Request::routeIs('menus.inicio') ? 'text-dark' : '' }}"></i>
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ Request::is('tickets*') ? 'active-link' : 'text-white' }}"
                        href="{{ route('tickets.index') }}">
                        <i class="fas fa-ticket-alt me-2 {{ Request::is('tickets*') ? 'text-dark' : '' }}"></i>
                        Ticket
                    </a>
                </li>
                @if ($user->rol == 0)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center has-submenu {{ Request::is('equipos*') || Request::is('impresoras*') ? 'active-link' : 'text-white' }} {{ Request::is('equipos*') || Request::is('impresoras*') ? 'active' : '' }}"
                            href="#" id="inventarioLink">
                            <i class="fas fa-laptop me-2 {{ Request::is('equipos*') || Request::is('impresoras*') ? 'text-dark' : '' }}"></i>
                            Inventario
                        </a>
                        <ul class="submenu {{ Request::is('equipos*') || Request::is('impresoras*') ? 'show' : '' }}" id="inventarioSubmenu">
                            <li>
                                <a class="nav-link d-flex align-items-center {{ Request::is('equipos*') ? 'active-submenu-link' : 'text-white' }}"
                                    href="{{ route('equipos.index') }}">
                                    <i class="fas fa-desktop me-2"></i> 
                                    Computadoras
                                </a>
                            </li>
                            <li>
                                <a class="nav-link d-flex align-items-center {{ Request::is('impresoras*') ? 'active-submenu-link' : 'text-white' }}"
                                    href="{{ route('impresoras.index') }}">
                                    <i class="fas fa-print me-2"></i> 
                                    Impresoras
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ Request::routeIs('usuarios.index') ? 'active-link' : 'text-white' }}"
                            href="{{ route('usuarios.index') }}">
                            <i class="fas fa-users-cog me-2 {{ Request::routeIs('usuarios.index') ? 'text-dark' : '' }}"></i>
                            Gestor de Usuarios
                        </a>
                    </li>
                @endif
            </ul>

            <hr class="bg-secondary">

            <div class="dropup mt-auto">
                <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#"
                    id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                    <i class="fas fa-user me-2"></i>
                    {{ $user->nombres }}
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownUser">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container-fluid p-3" style="margin-left: 250px;">
            @yield('content')
            @yield('scripts')
            @stack('scripts')
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS (Popper incl.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inventarioLink = document.getElementById('inventarioLink');
            const inventarioSubmenu = document.getElementById('inventarioSubmenu');

            inventarioLink.addEventListener('click', function (e) {
                e.preventDefault();
                
                // Alternar la clase 'show' en el submenú
                inventarioSubmenu.classList.toggle('show');
                
                // Alternar la clase 'active' en el enlace padre para la flecha
                this.classList.toggle('active');
            });

            // Mantener el submenú abierto si estamos en alguna de sus páginas
            if (window.location.pathname.includes('/equipos') || window.location.pathname.includes('/impresoras')) {
                inventarioSubmenu.classList.add('show');
                inventarioLink.classList.add('active');
            }
        });
    </script>
</body>

</html>
