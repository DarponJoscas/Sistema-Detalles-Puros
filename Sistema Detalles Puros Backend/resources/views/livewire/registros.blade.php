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
                                                    @if ($dato['estado_cliente'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editCliente({{ $dato['id_cliente'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteCliente({{ $dato['id_cliente'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarCliente('{{ $dato['id_cliente'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                    @if ($dato['estado_rol'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editRol({{ $dato['id_rol'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteRol({{ $dato['id_rol'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarRol('{{ $dato['id_rol'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                    @if ($dato['estado_marca'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editMarca({{ $dato['id_marca'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteMarca({{ $dato['id_marca'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarMarca('{{ $dato['id_marca'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                <td class="truncate-text" title="{{ $dato['capa'] }}">
                                                    {{ \Illuminate\Support\Str::limit($dato['capa'], 10, '...') }}</td>
                                                <td>
                                                    @if ($dato['estado_capa'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editCapa({{ $dato['id_capa'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteCapa({{ $dato['id_capa'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarCapa('{{ $dato['id_capa'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                    @if ($dato['estado_vitola'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editVitola({{ $dato['id_vitola'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteVitola({{ $dato['id_vitola'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarVitola('{{ $dato['id_vitola'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                    @if ($dato['estado_aliasVitola'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editAliasVitola({{ $dato['id_aliasvitola'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteAliasVitola({{ $dato['id_aliasvitola'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarAliasVitola('{{ $dato['id_aliasvitola'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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
                                                    @if ($dato['estado_tipoEmpaque'] == 1)
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            wire:click="editTipoEmpaque({{ $dato['id_tipoempaque'] }})">
                                                            <i class="bi bi-pencil-square text-white"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm m-1"
                                                            wire:click="deleteTipoEmpaque({{ $dato['id_tipoempaque'] }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success"
                                                            wire:click="reactivarTipoEmpaque('{{ $dato['id_tipoempaque'] }}')">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    @endif
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

    <!-- Modal para crear/editar un cliente -->
    <div class="modal fade" id="createClienteModal" tabindex="-1" aria-labelledby="createClienteModalLabel"
        wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClienteModalLabel">
                        @if ($id_cliente)
                            Editar Cliente
                        @else
                            Crear Cliente
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="cliente-nombre">
                                @if ($id_cliente)
                                    Editar Cliente
                                @else
                                    Crear Cliente
                                @endif
                            </label>
                            <input wire:model.defer="name_cliente" id="cliente-nombre" class="form-control"
                                placeholder="Ingrese el nombre del cliente">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeClienteModal">Cerrar</button>
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
                    <h5 class="modal-title" id="createRolModalLabel">
                        @if ($id_rol)
                            Editar Rol
                        @else
                            Crear Rol
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="rol-nombre">
                                @if ($id_rol)
                                    Editar Rol:
                                @else
                                    Crear Rol:
                                @endif
                            </label>
                            <input wire:model="rol" id="rol-nombre" class="form-control"
                                placeholder="Ingrese el nombre del rol">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeRolModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearRol">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear marca -->
    <div class="modal fade" id="createMarcaModal" tabindex="-1" aria-labelledby="createMarcaModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMarcaModalLabel">
                        @if ($id_marca)
                            Editar Marca
                        @else
                            Crear Marca
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="marca-nombre">
                                @if ($id_marca)
                                    Editar Marca:
                                @else
                                    Crear Marca:
                                @endif
                            </label>
                            <input wire:model="marca" id="marca-nombre" class="form-control"
                                placeholder="Ingrese el nombre de la marca">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeMarcaModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearMarca">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear capa -->
    <div class="modal fade" id="createCapaModal" tabindex="-1" aria-labelledby="createCapaLabel" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCapaLabel">
                        @if ($id_capa)
                            Editar Capa
                        @else
                            Crear Capa
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="nueva-capa">
                                @if ($id_capa)
                                    Editar Capa:
                                @else
                                    Crear Capa:
                                @endif
                            </label>
                            <input type="text" wire:model="capa" id="nueva-capa" class="form-control"
                                placeholder="Ingrese el nombre de la capa">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click ="closeCapaModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearCapa">Guardar</button>
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
                    <h5 class="modal-title" id="createVitolaModalLabel">
                        @if ($id_vitola)
                            Editar Vitola
                        @else
                            Crear Vitola
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="vitola-nombre">
                                @if ($id_vitola)
                                    Editar Vitola:
                                @else
                                    Crear Vitola:
                                @endif
                            </label>
                            <input type="text" wire:model="vitola" id="vitola-nombre" class="form-control"
                                placeholder="Ingrese el nombre de la vitola">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeVitolaModal">Cerrar</button>
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
                    <h5 class="modal-title" id="createAliasVitolaModalLabel">
                        @if ($id_aliasvitola)
                            Editar Alias Vitola
                        @else
                            Crear Alias Vitola
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="alias-vitola-nombre">
                                @if ($id_aliasvitola)
                                    Editar Alias Vitola:
                                @else
                                    Crear Alias Vitola:
                                @endif
                            </label>
                            <input type="text" wire:model="alias_vitola" id="alias-vitola-nombre"
                                class="form-control" placeholder="Ingrese el alias de la vitola">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeAliasVitolaModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearAliasVitola">Guardar</button>
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
                    <h5 class="modal-title" id="createTipoEmpaqueModalLabel">
                        @if ($id_tipoempaque)
                            Editar Tipo Empaque
                        @else
                            Crear Tipo Empaque
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="tipo-empaque-nombre">
                                @if ($id_tipoempaque)
                                    Editar Tipo Empaque
                                @else
                                    Crear Tipo Empaque
                                @endif
                            </label>
                            <input type="text" wire:model="tipo_empaque" id="tipo-empaque-nombre"
                                class="form-control" placeholder="Ingrese el tipo de empaque">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeTipoEmpaqueModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearTipoEmpaque">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const clienteModal = new bootstrap.Modal(document.getElementById('createClienteModal'));

                Livewire.on('hide-cliente-modal', () => {
                    clienteModal.hide();
                });

                Livewire.on('show-cliente-modal', () => {
                    clienteModal.show();
                });

                const rolModal = new bootstrap.Modal(document.getElementById('createRolModal'));

                Livewire.on('hide-rol-modal', () => {
                    rolModal.hide();
                });

                Livewire.on('show-rol-modal', () => {
                    rolModal.show();
                });

                const marcaModal = new bootstrap.Modal(document.getElementById('createMarcaModal'));

                Livewire.on('hide-marca-modal', () => {
                    marcaModal.hide();
                });

                Livewire.on('show-marca-modal', () => {
                    marcaModal.show();
                });

                const capaModal = new bootstrap.Modal(document.getElementById('createCapaModal'));

                Livewire.on('hide-capa-modal', () => {
                    capaModal.hide();
                });

                Livewire.on('show-capa-modal', () => {
                    capaModal.show();
                });

                const vitolaModal = new bootstrap.Modal(document.getElementById('createVitolaModal'));

                Livewire.on('hide-vitola-modal', () => {
                    vitolaModal.hide();
                });

                Livewire.on('show-vitola-modal', () => {
                    vitolaModal.show();
                });

                const aliasVitolaModal = new bootstrap.Modal(document.getElementById('createAliasVitolaModal'));

                Livewire.on('hide-aliasVitola-modal', () => {
                    aliasVitolaModal.hide();
                });

                Livewire.on('show-aliasVitola-modal', () => {
                    aliasVitolaModal.show();
                });
                const tipoEmpaqueModal = new bootstrap.Modal(document.getElementById('createTipoEmpaqueModal'));

                Livewire.on('hide-tipoEmpaque-modal', () => {
                    tipoEmpaqueModal.hide();
                });

                Livewire.on('show-tipoEmpaque-modal', () => {
                    tipoEmpaqueModal.show();
                });

                new TomSelect('#aliasVitola', {
                    create: true
                });
                new TomSelect('#cliente', {
                    create: true
                });
                new TomSelect('#roles', {
                    create: true
                });
                new TomSelect('#marcas', {
                    create: true
                });
                new TomSelect('#vitola', {
                    create: true
                });
                new TomSelect('#capa', {
                    create: true
                });
                new TomSelect('#tipoEmpaque', {
                    create: true
                });
            });
        </script>
    @endpush
</div>
