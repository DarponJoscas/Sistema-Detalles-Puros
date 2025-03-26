<div>
    <div class="d-inline-block m-3" style=" z-index: -800; position: absolute;">
        <!-- Fila única con desplazamiento horizontal -->
        <div class="container-fluid">

            <div class="row flex-wrap justify-content-between flex-md-row flex-column">
                <!-- Columna de Clientes -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Clientes</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createClienteModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3" style=" z-index: -800;">
                                <select id="cliente" wire:model="filtro_cliente" wire:change="filtrar">
                                    <option value="">Buscar cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->name_cliente }}">{{ $cliente->name_cliente }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Cliente</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosClientes as $dato)
                                            <tr>
                                                <td>{{ $dato['id_cliente'] }}</td>
                                                <td>{{ $dato['name_cliente'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editCliente({{ $dato['id_cliente'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteCliente({{ $dato['id_cliente'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Roles -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Roles</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createRolModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="roles" wire:model="filtro_rol" wire:change="filtrar">
                                    <option value="">Buscar rol</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->rol }}">{{ $rol->rol }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Rol</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosRoles as $dato)
                                            <tr>
                                                <td>{{ $dato['id_rol'] }}</td>
                                                <td>{{ $dato['rol'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editCliente({{ $dato['id_rol'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteCliente({{ $dato['id_rol'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Marcas -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Marcas</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createMarcaModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="marcas" wire:model="filtro_marca" wire:change="filtrar">
                                    <option value="">Buscar marca</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->marca }}">{{ $marca->marca }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Marca</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosMarcas as $dato)
                                            <tr>
                                                <td>{{ $dato['id_marca'] }}</td>
                                                <td>{{ $dato['marca'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editCliente({{ $dato['id_marca'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteCliente({{ $dato['id_marca'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Vitolas -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div wire.ignore class="card-header d-flex justify-content-between align-items-center">
                            <span>Vitolas</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createVitolaModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="vitola" wire:model="filtro_vitola" wire:change="filtrar">
                                    <option value="">Buscar vitola</option>
                                    @foreach ($vitolas as $vitola)
                                        <option value="{{ $vitola->vitola }}">{{ $vitola->vitola }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Vitola</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosVitolas as $dato)
                                            <tr>
                                                <td>{{ $dato['id_vitola'] }}</td>
                                                <td>{{ $dato['vitola'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editVitola({{ $dato['id_vitola'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteVitola({{ $dato['id_vitola'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar esta vitola?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Capas -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Capas</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createCapaModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="capa" wire:model="filtro_capa" wire:change="filtrar">
                                    <option value="">Buscar capa</option>
                                    @foreach ($capas as $capa)
                                        <option value="{{ $capa->capa }}">{{ $capa->capa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Capa</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosCapas as $dato)
                                            <tr>
                                                <td>{{ $dato['id_capa'] }}</td>
                                                <td>{{ $dato['capa'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editCapa({{ $dato['id_capa'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteCapa({{ $dato['id_capa'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar esta capa?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Tipos de Empaque -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Tipos de Empaque</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createTipoEmpaqueModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="tipoEmpaque" wire:model="filtro_tipoEmpaque" wire:change="filtrar">
                                    <option value="">Buscar tipo empaque</option>
                                    @foreach ($tipo_empaques as $tipo)
                                        <option value="{{ $tipo->tipo_empaque }}">{{ $tipo->tipo_empaque }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Tipo Empaque</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosTipoEmpaque as $dato)
                                            <tr>
                                                <td>{{ $dato['id_tipoempaque'] }}</td>
                                                <td>{{ $dato['tipo_empaque'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editTipoEmpaque({{ $dato['id_tipoempaque'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteTipoEmpaque({{ $dato['id_tipoempaque'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar este tipo de empaque?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna de Alias Vitolas -->
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Alias Vitolas</span>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createAliasVitolaModal">
                                <i class="bi bi-plus-circle"></i> Crear
                            </button>
                        </div>
                        <div class="card-body">
                            <div wire:ignore class="mb-3">
                                <select id="aliasVitola" wire:model="filtro_aliasVitola" wire:change="filtrar">
                                    <option value="">Buscar alias vitola</option>
                                    @foreach ($alias_vitolas as $alias_vitola)
                                        <option value="{{ $alias_vitola->alias_vitola }}">
                                            {{ $alias_vitola->alias_vitola }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-sm table-hover text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Alias Vitola</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosAliasVitolas as $dato)
                                            <tr>
                                                <td>{{ $dato['id_aliasvitola'] }}</td>
                                                <td>{{ $dato['alias_vitola'] }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        wire:click="editAliasVitola({{ $dato['id_aliasvitola'] }})">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm m-1"
                                                        wire:click="deleteAliasVitola({{ $dato['id_aliasvitola'] }})"
                                                        onclick="return confirm('¿Está seguro de eliminar este alias de vitola?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear un cliente-->
    <div class="modal fade" id="createClienteModal" tabindex="-1" aria-labelledby="createClienteModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClienteModalLabel">Crear Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label type="text" for="cliente-nombre">Nombre del Cliente:</label>
                            <input wire:model="name_cliente" id="cliente-nombre" class="form-control"
                                placeholder="Ingrese el nombre del cliente">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearCliente">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear rol -->
    <div class="modal fade" id="createRolModal" tabindex="-1" aria-labelledby="createRolModalLabel" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRolModalLabel">Crear Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="rol-nombre">Nombre del Rol:</label>
                            <input wire:model="rol" id="rol-nombre" class="form-control"
                                placeholder="Ingrese el nombre del rol">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearRol">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear marca -->
    <div class="modal fade" id="createMarcaModal" tabindex="-1" aria-labelledby="createMarcaModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMarcaModalLabel">Crear Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="marca-nombre">Nombre de la Marca:</label>
                            <input wire:model="marca" id="marca-nombre" class="form-control"
                                placeholder="Ingrese el nombre de la marca">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearMarca">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear vitola -->
    <div class="modal fade" id="createVitolaModal" tabindex="-1" aria-labelledby="createVitolaModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createVitolaModalLabel">Crear Vitola</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="vitola-nombre">Nombre de la Vitola:</label>
                            <input type="text" wire:model="vitola" id="vitola-nombre" class="form-control"
                                placeholder="Ingrese el nombre de la vitola">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearVitola">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear alias vitola -->
    <div class="modal fade" id="createAliasVitolaModal" tabindex="-1" aria-labelledby="createAliasVitolaModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAliasVitolaModalLabel">Nuevo Alias Vitola</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="alias-vitola-nombre">Nuevo Alias Vitola:</label>
                            <input type="text" wire:model="alias_vitola" id="alias-vitola-nombre"
                                class="form-control" placeholder="Ingrese el alias de la vitola">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearAliasVitola">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear capa -->
    <div class="modal fade" id="createCapaModal" tabindex="-1" aria-labelledby="createCapaLabel" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCapaLabel">Crear Capa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="nueva-capa">Nueva Capa:</label>
                            <input type="text" wire:model="capa" id="nueva-capa" class="form-control"
                                placeholder="Ingrese el nombre de la capa">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearCap">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para crear tipo empaque -->
    <div class="modal fade" id="createTipoEmpaqueModal" tabindex="-1" aria-labelledby="createTipoEmpaqueModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTipoEmpaqueModalLabel">Crear Tipo Empaque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="tipo-empaque-nombre">Nuevo Tipo Empaque:</label>
                            <input type="text" wire:model="tipo_empaque" id="tipo-empaque-nombre"
                                class="form-control" placeholder="Ingrese el tipo de empaque">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearTipoEmpaque">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear un alias vitola -->
    <div class="modal fade" id="createAliasVitolaModal" tabindex="-1" aria-labelledby="createAliasVitolaLabel"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAliasVitolaLabel">Nuevo Alias Vitola</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="alias-vitola">Nuevo Alias Vitola:</label>
                            <input wire:model="alias_vitola" id="alias-vitola" class="form-control"
                                placeholder="Ingrese el alias">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearAliasVitola">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const createClienteModal = new bootstrap.Modal(document.getElementById('createClienteModal'));
                Livewire.on('hideClienteModal', () => {
                    createClienteModal.hide();
                });

                const CrearRolModal = new bootstrap.Modal(document.getElementById('createRolModal'));
                Livewire.on('hideRolModal', () => {
                    createRolModal.hide();
                });

                const createMarcaModal = new bootstrap.Modal(document.getElementById('createMarcaModal'));
                Livewire.on('hideMarcaModal', () => {
                    createMarcaModal.hide();
                });

                const createVitolaModal = new bootstrap.Modal(document.getElementById('createVitolaModal'));
                Livewire.on('hideVitolaModal', () => {
                    createVitolaModal.hide();
                });

                const createCapaModal = new bootstrap.Modal(document.getElementById('createCapaModal'));
                Livewire.on('hideCapaModal', () => {
                    createCapaModal.hide();
                });

                const createAliasVitolaModal = new bootstrap.Modal(document.getElementById('createAliasVitolaModal'));
                Livewire.on('hideAliasVitolaModal', () => {
                    createAliasVitolaModal.hide();
                });

                const createTipoEmpaqueModal = new bootstrap.Modal(document.getElementById('createTipoEmpaqueModal'));
                Livewire.on('hideTipoEmpaqueModal', () => {
                    createTipoEmpaqueModal.hide();
                });

                new TomSelect('#aliasVitola');
                new TomSelect('#cliente');
                new TomSelect('#roles');
                new TomSelect('#marcas');
                new TomSelect('#vitola');
                new TomSelect('#capa');
                new TomSelect('#tipoEmpaque');
            });
        </script>
    @endpush
</div>
