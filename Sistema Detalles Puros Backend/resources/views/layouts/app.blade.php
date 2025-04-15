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
                                    style="color: white; text-decoration: none;">Información de Puros</button>
                            </form>
                        </li>
                    @endif

                    @if (!in_array(request()->path(), ['infoempaque']))
                        <li class="nav-item">
                            <form action="{{ route('infoempaque') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Información de Empaque</button>
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

                    @if (!in_array(request()->path(), ['historialimagenes']))
                        <li class="nav-item">
                            <form action="{{ route('historialimagenes') }}" method="GET">
                                <button type="submit" class="nav-link btn btn-link"
                                    style="color: white; text-decoration: none;">Historial de Imagenes</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('swal', (data) => {
                const options = JSON.parse(data);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: options.timer || 3000,
                    timerProgressBar: true,
                    icon: options.icon || 'success',
                    title: options.title || '¡Hecho!',
                    text: options.text || '',
                });
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:init', function() {
            Livewire.on('swalConfirmDelete', (data) => {
                const options = JSON.parse(data);

                const keys = Object.keys(options);
                const idKey = keys.find(k => k.startsWith('id'));
                const idValue = options[idKey];

                delete options[idKey];

                Swal.fire(options).then((result) => {
                    if (result.isConfirmed) {
                        if (idKey === 'idPedido') {
                            Livewire.dispatch('confirmarEliminacionPedido', {
                                id_pedido: idValue
                            });
                        }

                        if (idKey === 'idPuro') {
                            Livewire.dispatch('confirmarEliminacionPuro', {
                                idPuro: idValue
                            });
                        }

                        if (idKey === 'idCliente') {
                            Livewire.dispatch('confirmarEliminacionCliente', {
                                clienteId: idValue
                            });
                        }

                        if (idKey === 'idRol') {
                            Livewire.dispatch('confirmarEliminacionRol', {
                                rolId: idValue
                            });
                        }

                        if (idKey === 'idMarca') {
                            Livewire.dispatch('confirmarEliminacionMarca', {
                                marcaId: idValue
                            });
                        }

                        if (idKey === 'idCapa') {
                            Livewire.dispatch('confirmarEliminacionCapa', {
                                capaId: idValue
                            });
                        }

                        if (idKey === 'idVitola') {
                            Livewire.dispatch('confirmarEliminacionVitola', {
                                vitolaId: idValue
                            });
                        }

                        if (idKey === 'idAliasVitola') {
                            Livewire.dispatch('confirmarEliminacionAliasVitola', {
                                aliasVitolaId: idValue
                            });
                        }

                        if (idKey === 'idTipoEmpaque') {
                            Livewire.dispatch('confirmarEliminacionTipoEmpaque', {
                                tipoEmpaqueId: idValue
                            });
                        }

                        if (idKey === 'idUsuario') {
                            Livewire.dispatch('confirmarEliminacionUsuario', {
                                id_usuario: idValue
                            });
                        }
                    }
                });
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectIds = [
            'cliente',
            'codigoPuro',
            'marca',
            'vitola',
            'aliasVitola',
            'capa',
            'codigoItem'
        ];

        function initTomSelects() {
            selectIds.forEach(id => {
                let existingSelect = document.querySelector(`#${id}`);
                if (existingSelect && !existingSelect.tomselect) {
                    new TomSelect(`#${id}`, {
                        placeholder: existingSelect.options[0]?.text || 'Seleccionar',
                        onFocus: function () {
                            let firstOption = this.input.querySelector('option[value=""]');
                            if (firstOption) firstOption.remove();
                        }
                    });
                }
            });
        }

        initTomSelects();

        Livewire.hook('message.processed', () => {
            initTomSelects();
        });

        Livewire.on('resetClienteSelect', (clientes) => {
            let select = TomSelect.getInstance(document.querySelector("#cliente"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar un cliente" });

                clientes.forEach(cliente => {
                    select.addOption({ value: cliente.name_cliente, text: cliente.name_cliente });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetCodigoPuroSelect', (puros) => {
            let select = TomSelect.getInstance(document.querySelector("#codigoPuro"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar un código puro" });

                puros.forEach(puro => {
                    select.addOption({ value: puro.codigo_puro, text: puro.codigo_puro });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetMarcaSelect', (marcas) => {
            let select = TomSelect.getInstance(document.querySelector("#marca"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar marca" });

                marcas.forEach(marca => {
                    select.addOption({ value: marca.marca, text: marca.marca });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetVitolaSelect', (vitolas) => {
            let select = TomSelect.getInstance(document.querySelector("#vitola"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar vitola" });

                vitolas.forEach(vitola => {
                    select.addOption({ value: vitola.vitola, text: vitola.vitola });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetAliasVitolaSelect', (alias_vitolas) => {
            let select = TomSelect.getInstance(document.querySelector("#aliasVitola"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar alias vitola" });

                alias_vitolas.forEach(alias => {
                    select.addOption({ value: alias.alias_vitola, text: alias.alias_vitola });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetCapaSelect', (capas) => {
            let select = TomSelect.getInstance(document.querySelector("#capa"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar capa" });

                capas.forEach(capa => {
                    select.addOption({ value: capa.capa, text: capa.capa });
                });

                select.refreshOptions();
            }
        });

        Livewire.on('resetCodigoItemSelect', (empaques) => {
            let select = TomSelect.getInstance(document.querySelector("#codigoItem"));
            if (select) {
                select.clear();
                select.clearOptions();
                select.addOption({ value: "", text: "Buscar item" });

                empaques.forEach(empaque => {
                    select.addOption({ value: empaque.codigo_empaque, text: empaque.codigo_empaque });
                });

                select.refreshOptions();
            }
        });
    });
</script>



</body>

</html>
