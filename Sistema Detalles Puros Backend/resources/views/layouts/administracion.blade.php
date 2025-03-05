<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Detalle Puro')</title>
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #343a40;
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
            background-color: #343a40;
            padding: 5px 15px;
            height: 30px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        #hamburger {
            font-size: 20px;
            color: white;
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 8px 5px;
            transition: color 0.2s;
        }

        #hamburger:hover {
            color: #adb5bd;
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
    @if (!in_array(request()->path(), ['login', 'dashboard']))
    <div id="navbar-content" class="navbar-content">

        <nav id="navbar" class="navbar navbar-expand-lg">
            <button id="hamburger" aria-label="Menú"><i class="fas fa-bars"></i></button>
        </nav>

        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <nav id="sidebar">
            <button id="closeSidebar" aria-label="Cerrar menú">&times;</button>
            <h4>Menú</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <form action="{{ route('dashboard') }}" method="GET">

                        <button type="submit" class="nav-link btn btn-link" style="color: white; text-decoration: none;">Inicio</button>
                    </form>
                </li>

                @if (!in_array(request()->path(), ['administracion']))
                    <li class="nav-item">
                        <form action="{{ route('administracion') }}" method="GET">

                            <button type="submit" class="nav-link btn btn-link" style="color: white; text-decoration: none;">Administración</button>
                        </form>
                    </li>
                @endif

                <li class="nav-item">
                    <form action="{{ route('registrarusuario') }}" method="GET">
                        <button type="submit" class="nav-link btn btn-link" style="color: white; text-decoration: none;">Registrar Usuario</button>
                    </form>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        <button type="submit" class="nav-link btn btn-link" style="color: white; text-decoration: none;">Cerrar sesión</button>
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
    </script>
</body>
</html>
