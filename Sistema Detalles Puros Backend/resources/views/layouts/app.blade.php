<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Detalle Puro</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #000000;
            color: white;
            padding: 20px;
            transition: left 0.3s ease;
            z-index: 1030;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        #sidebar.active {
            left: 0;
        }

        .navbar-content {
            padding-bottom: 10px;
            transition: margin-left 0.3s ease;
            position: relative;
            z-index: 1;
            margin-left: 0;
        }

        .main-content.sidebar-open {
            margin-left: 250px;
        }

        #navbar {
            display: flex;
            background-color: #000000;
            justify-content: space-between;
            align-items: center;
            padding: 2px 10px;
        }

        #hamburger {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        #hamburger:hover {
            color: #adb5bd;
        }

        #usarioLogin {
            margin: 0;
        }

        #hamburger:focus {
            outline: none;
        }

        #closeSidebar {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }

        .nav-link {
            color: white;
        }

        .nav-link:hover {
            color: #adb5bd;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1029;
        }
    </style>
</head>

<body>

    @if (!in_array(request()->path(), ['/', 'dashboard']))
        <div id="navbar-content" class="navbar-content">

            <nav id="navbar" class="navbar navbar-expand-lg">
                @if ($id_rol != 2 && $id_rol != 3)
                    <button id="hamburger" aria-label="Menú"><i class="bi bi-list text-white"></i></button>
                @endif

                <p id="usarioLogin" style="color: white;">Bienvenido, {{ $name_usuario }}</p>
                <i id="logout-icon" style="color: white;" class="bi bi-power"></i>
            </nav>

            <div class="sidebar-overlay" id="sidebar-overlay"></div>

            <nav id="sidebar">
                <button id="closeSidebar" aria-label="Cerrar menú"><i class="bi bi-x"></i></button>
                <h4>Menú</h4>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <form action="{{ route('dashboard') }}" method="GET">
                            <button type="submit" class="nav-link btn btn-link"
                                style="color: white; text-decoration: none;">Inicio</button>
                        </form>
                    </li>

                    @if (!in_array(request()->path(), ['administracion']))
                        <li class="nav-item">
                            <form action="{{ route('administracion') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Administración</button>
                            </form>
                        </li>
                    @endif


                    @if (!in_array(request()->path(), ['empaque']))
                        <li class="nav-item">
                            <form action="{{ route('empaque') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Empaque</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['produccion']))
                        <li class="nav-item">
                            <form action="{{ route('produccion') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Producción</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['puros']))
                        <li class="nav-item">
                            <form action="{{ route('puros') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Puros</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['registros']))
                        <li class="nav-item">
                            <form action="{{ route('registros') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Registros</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['usuarios']))
                        <li class="nav-item">
                            <form action="{{ route('usuarios') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Usuarios</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['bitacora']))
                        <li class="nav-item">
                            <form action="{{ route('bitacora') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Bitacora</button>
                            </form>
                        </li>
                    @endif

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link"
                                style="color: white; text-decoration: none;">Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    @endif

    @yield('content')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    @stack('scripts')

    <script>
        const hamburger = document.getElementById("hamburger");
        const closeSidebar = document.getElementById("closeSidebar");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const overlay = document.getElementById("sidebar-overlay");

        function openSidebar() {
            sidebar.classList.add("active");
            if (mainContent) {
                mainContent.classList.add("sidebar-open");
            }
            overlay.style.display = "block";
        }

        function closeSidebarFunc() {
            sidebar.classList.remove("active");
            if (mainContent) {
                mainContent.classList.remove("sidebar-open");
            }
            overlay.style.display = "none";
        }

        if (hamburger) {
            hamburger.addEventListener("click", openSidebar);
        }
        if (closeSidebar) {
            closeSidebar.addEventListener("click", closeSidebarFunc);
        }
        if (overlay) {
            overlay.addEventListener("click", closeSidebarFunc);
        }

        // Cargar nombre del usuario desde la API
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Tom Select
            new TomSelect('#codigoPuro');
            new TomSelect('#presentacionPuro');
            new TomSelect('#marca');
            new TomSelect('#vitola');
            new TomSelect('#aliasVitola');
            new TomSelect('#capa');
            new TomSelect('#cliente');
            new TomSelect('#codigoItem');
            new TomSelect('#select-cliente');
            new TomSelect('#select-codigo-puro');
            new TomSelect('#roles');
            new TomSelect('#marcas');
            new TomSelect('#tipoEmpaque');


        });

        document.addEventListener('DOMContentLoaded', function() {
            const logoutIcon = document.getElementById('logout-icon');

            logoutIcon.addEventListener('click', function() {

                const logoutForm = document.createElement('form');
                logoutForm.method = 'POST';
                logoutForm.action = '{{ route('logout') }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                logoutForm.appendChild(csrfToken);
                document.body.appendChild(logoutForm);

                logoutForm.submit();
            });
        });
    </script>
</body>

</html>
