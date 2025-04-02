<div>
    <div>
        <div class="d-inline-block m-3" style="z-index: -800; position: absolute;">
            <div wire:ignore class="row g-0">
                <div class="col px-1" style="width: 160px; flex: none;">
                    <select id="cliente" wire:model="filtro_cliente" wire:change="filtrarPedidos">
                        <option value="">Buscar un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->name_cliente }}">{{ $cliente->name_cliente }}</option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                    <select id="codigoPuro" wire:model="filtro_codigo_puro" wire:change="filtrarPedidos">
                        <option value="">Buscar un código puro</option>
                        @foreach ($puros as $puro)
                            <option value="{{ $puro->codigo_puro }}">{{ $puro->codigo_puro }}</option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 190px; flex: none;">
                    <select id="presentacionPuro" wire:model="filtro_presentacion" wire:change="filtrarPedidos">
                        <option value="">Buscar presentación puro</option>
                        @foreach ($presentaciones as $presentacion)
                            <option value="{{ $presentacion->presentacion_puro }}">
                                {{ $presentacion->presentacion_puro }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                    <select id="marca" wire:model="filtro_marca" wire:change="filtrarPedidos">
                        <option value="">Buscar marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->marca }}">{{ $marca->marca }}</option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                    <select id="vitola" wire:model="filtro_vitola" wire:change="filtrarPedidos">
                        <option value="">Buscar vitola</option>
                        @foreach ($vitolas as $vitola)
                            <option value="{{ $vitola->vitola }}">{{ $vitola->vitola }}</option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                    <select id="aliasVitola" wire:model="filtro_alias_vitola" wire:change="filtrarPedidos">
                        <option value="">Buscar alias vitola</option>
                        @foreach ($alias_vitolas as $alias_vitola)
                            <option value="{{ $alias_vitola->alias_vitola }}">{{ $alias_vitola->alias_vitola }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                    <select id="capa" wire:model="filtro_capa" wire:change="filtrarPedidos">
                        <option value="">Buscar capa</option>
                        @foreach ($capas as $capa)
                            <option value="{{ $capa->capa }}">{{ $capa->capa }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col px-1" style="width: 160px; flex: none;">
                    <select id="codigoItem" wire:model="filtro_codigo_empaque" wire:change="filtrarPedidos">
                        <option value="">Buscar item</option>
                        @foreach ($empaques as $empaque)
                            <option value="{{ $empaque->codigo_empaque }}">{{ $empaque->codigo_empaque }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-2">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                    Resetear Filtros
                </button>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#registrarpedidoempaqueModal">
                    Registrar Nuevo Pedido
                </button>
            </div>

            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Vista para mostrar los registros de Pedido-->
            <div class="table-responsive text-center" wire:ignore.self>
                <table class="table mt-3 mx-auto text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th>N° Pedido</th>
                            <th>Cliente</th>
                            <th>Código Puro</th>
                            <th>Presentación</th>
                            <th>Marca</th>
                            <th>Vitola</th>
                            <th>Alias Vitola</th>
                            <th>Capa</th>
                            <th>Descripción Producción</th>
                            <th>Imagen Producción</th>
                            <th>Código Empaque</th>
                            <th>Sampler</th>
                            <th>Descripción Empaque</th>
                            <th>Anillo</th>
                            <th>Imagen Anillado</th>
                            <th>Sello</th>
                            <th>UPC</th>
                            <th>Tipo Empaque</th>
                            <th>Código Caja</th>
                            <th>Imagen Caja</th>
                            <th>Estado Pedido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr>
                                <td>{{ $dato['id_pedido'] }}</td>
                                <td>{{ $dato['cliente'] }}</td>
                                <td>{{ $dato['codigo_puro'] }}</td>
                                <td>{{ $dato['presentacion_puro'] }}</td>
                                <td>{{ $dato['marca'] }}</td>
                                <td>{{ $dato['vitola'] }}</td>
                                <td>{{ $dato['alias_vitola'] }}</td>
                                <td>{{ $dato['capa'] }}</td>
                                <td>{{ $dato['descripcion_produccion'] }}</td>
                                <td>
                                    @if ($dato['imagen_produccion'])
                                        <img src="{{ asset('storage/' . $dato['imagen_produccion']) }}"
                                            alt="Imagen Producción" width="100" height="100"
                                            class="d-block mx-auto rounded">
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $dato['codigo_empaque'] }}</td>
                                <td>{{ $dato['sampler'] }}</td>
                                <td>{{ $dato['descripcion_empaque'] }}</td>
                                <td>{{ $dato['anillo'] }}</td>
                                <td>
                                    @if ($dato['imagen_anillado'])
                                        <img src="{{ asset('storage/' . $dato['imagen_anillado']) }}"
                                            alt="Imagen Anillado" width="100" height="100"
                                            class="d-block mx-auto rounded">
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $dato['sello'] }}</td>
                                <td>{{ $dato['upc'] }}</td>
                                <td>{{ $dato['tipo_empaque'] }}</td>
                                <td>{{ $dato['codigo_caja'] }}</td>
                                <td>
                                    @if ($dato['imagen_caja'])
                                        <img src="{{ asset('storage/' . $dato['imagen_caja']) }}" alt="Imagen Caja"
                                            width="100" height="100" class="d-block mx-auto rounded">
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $dato['estado_pedido'] }}</td>
                                <td>
                                    <div class="d-inline-block m-1">
                                        <button type="button" class="btn btn-warning"
                                            wire:click="editarPedido({{ $dato['id_pedido'] }})">
                                            <i class="bi bi-pencil-square text-white"></i>
                                        </button>
                                    </div>

                                    <div class="d-inline-block">
                                        <button type="button" class="btn btn-danger"
                                            wire:click="eliminarPedido({{ $dato['id_pedido'] }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $datosPaginados->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Registro de Pedido-->
    <div class="modal fade" id="registrarpedidoempaqueModal" tabindex="-1" aria-labelledby="registrarPedidoLabel"
    wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registrarPedidoLabel">
                        @if ($id_pedido)
                            Editar Detalle Pedido
                        @else
                            Crear Detalle Pedido
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        title="Cerrar modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3" wire:ignore>
                            <label for="cliente">Cliente:</label>
                            <select wire:model="id_cliente" id="select-cliente">
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}">{{ $cliente->name_cliente }}</option>
                                @endforeach
                            </select>
                            @error('id_cliente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3" wire:ignore>
                            <label for="codigo_empaque">Código de Empaque:</label>
                            <select wire:model="codigo_empaque" id="select-codigo-empaque" wire:change="infoEmpaque">
                                <option value="">Seleccione un código de empaque</option>
                                @foreach ($empaques as $empaque)
                                    <option value="{{ $empaque->codigo_empaque }}">{{ $empaque->codigo_empaque }}
                                    </option>
                                @endforeach
                            </select>
                            @error('codigo_empaque')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="codigo_puro">Código de Puro:</label>
                            <input type="text" wire:model="codigo_puro" id="codigo_puro" class="form-control"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="presentacion_puro">Presentación del Puro:</label>
                            <input type="text" wire:model="presentacion_puro" id="presentacion_puro"
                                class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="marca">Marca del Puro:</label>
                            <input type="text" wire:model="marca" id="marca" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="alias_vitola">Alias de la Vitola:</label>
                            <input type="text" wire:model="alias_vitola" id="alias_vitola" class="form-control"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="vitola">Vitola:</label>
                            <input type="text" wire:model="vitola" id="vitola" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="capa">Capa del Puro:</label>
                            <input type="text" wire:model="capa" id="capa" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipo_empaque">Tipo de Empaque:</label>
                            <input type="text" wire:model="tipo_empaque" id="tipo_empaque" class="form-control"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="sampler">Sampler:</label>
                            <input type="text" wire:model="sampler" id="sampler" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion_empaque">Descripción Empaque:</label>
                            <input type="text" wire:model="descripcion_empaque" id="descripcion_empaque"
                                class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="anillo">Anillo:</label>
                            <input type="text" wire:model="anillo" id="anillo" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen_anillado">Imagen de Anillado:</label>
                            <input type="file" wire:model="imagen_anillado" id="imagen_anillado"
                                class="form-control">
                            @if ($imagen_anillado && is_object($imagen_anillado))
                                <div class="mt-2">
                                    <img src="{{ $imagen_anillado->temporaryUrl() }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @elseif ($imagen_anillado && !is_object($imagen_anillado))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $imagen_anillado) }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="sello">Sello:</label>
                            <input type="text" wire:model="sello" id="sello" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="upc">UPC:</label>
                            <input type="text" wire:model="upc" id="upc" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="codigo_caja">Código Caja:</label>
                            <input type="text" wire:model="codigo_caja" id="codigo_caja" class="form-control"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen_caja">Imagen de Caja:</label>
                            <input type="file" wire:model="imagen_caja" id="imagen_caja" class="form-control">
                            @if ($imagen_caja && is_object($imagen_caja))
                                <div class="mt-2">
                                    <img src="{{ $imagen_caja->temporaryUrl() }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @elseif ($imagen_caja && !is_object($imagen_caja))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $imagen_caja) }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion_produccion">Descripción Producción:</label>
                            <input type="text" wire:model="descripcion_produccion" id="descripcion_produccion"
                                class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen_produccion">Imagen de Producción:</label>
                            <input type="file" wire:model="imagen_produccion" id="imagen_produccion"
                                class="form-control">
                            @if ($imagen_produccion && is_object($imagen_produccion))
                                <div class="mt-2">
                                    <img src="{{ $imagen_produccion->temporaryUrl() }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @elseif ($imagen_produccion && !is_object($imagen_produccion))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $imagen_produccion) }}" class="img-thumbnail"
                                        width="150">
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeEmpaqueModal">Cerrar</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="crearDetallePedido">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const pedidoModal = new bootstrap.Modal(document.getElementById('registrarpedidoempaqueModal'));

                Livewire.on('hide-edit-modal', () => {
                    pedidoModal.hide();
                });

                Livewire.on('show-edit-modal', () => {
                    pedidoModal.show();
                });

                new TomSelect('#select-cliente');
                new TomSelect('#select-codigo-empaque');
            });

            function limpiarFiltros() {
                let selectIds = [
                    'cliente', 'codigoPuro', 'presentacionPuro', 'marca',
                    'vitola', 'aliasVitola', 'capa', 'codigoItem'
                ];

                selectIds.forEach(id => {
                    let selectElement = document.getElementById(id);
                    if (selectElement && selectElement.tomselect) {
                        selectElement.tomselect.clear();
                    }
                });

                Livewire.dispatch('resetFilters');
            }
        </script>
    @endpush

</div>
