<div>
    <style>
        .lightbox {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }
    </style>

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

            </div>

            <div class="mt-2">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                    Resetear Filtros
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
                            <th>Estado Pedido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr class="{{ $dato['estado_pedido'] == 0 ? 'table-secondary text-muted' : '' }}">
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
                                    @if (!empty($dato['imagen_produccion']) && is_array($dato['imagen_produccion']))
                                        <div class="d-flex justify-content-center flex-wrap gap-2">
                                            @foreach ($dato['imagen_produccion'] as $img)
                                                <img src="{{ asset('storage/' . $img) }}" alt="Imagen Producción"
                                                    width="80" height="80" class="rounded" style="cursor:pointer"
                                                    onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif

                                </td>
                                <td><span class="badge {{ $dato['estado_pedido'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $dato['estado_pedido'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span></td>
                                <td>
                                    <div class="d-inline-block m-1">
                                        <button type="button" class="btn btn-warning"
                                            wire:click="editarPedido({{ $dato['id_pedido'] }})"
                                            onclick="document.getElementById('select-cliente').tomselect.setValue({{ $dato['id_cliente'] }}); document.getElementById('select-codigo-empaque').tomselect.setValue({{ $dato['codigo_empaque'] }});">
                                            <i class="bi bi-pencil-square text-white"></i>
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
                    wire:click="closeEmpaqueModal" title="Cerrar modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="cliente">Cliente:</label>
                            <input type="text" class="form-control" id="cliente" value="{{ optional($clientes->firstWhere('id_cliente', $id_cliente))->name_cliente }}" readonly>
                            @error('id_cliente')
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
                            <label for="descripcion_produccion">Descripción Producción:</label>
                            <input type="text" wire:model="descripcion_produccion" id="descripcion_produccion"
                                class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen_produccion">Imágenes de Producción (máx. 3):</label>
                            <input type="file" wire:model="imagen_produccion_nuevas" id="imagen_produccion"
                                class="form-control" multiple accept="image/*">

                            @if ($imagen_produccion || $imagen_produccion_nuevas)
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    {{-- Existentes --}}
                                    @foreach ($imagen_produccion as $index => $imagen)
                                        @if (is_string($imagen))
                                            <div>
                                                <img src="{{ asset('storage/' . $imagen) }}" class="img-thumbnail"
                                                    width="150">
                                                <p class="text-center">Imagen guardada {{ $index + 1 }}</p>
                                                <button type="button"
                                                    wire:click="eliminarImagenExistente('produccion', {{ $index }})"
                                                    class="btn btn-sm btn-danger mt-1">Eliminar</button>
                                            </div>
                                        @endif
                                    @endforeach

                                    {{-- Nuevas --}}
                                    @foreach ($imagen_produccion_nuevas as $index => $imagen)
                                        <div>
                                            <img src="{{ $imagen->temporaryUrl() }}" class="img-thumbnail"
                                                width="150">
                                            <p class="text-center">Nueva Imagen {{ $index + 1 }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="closeEmpaqueModal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="crearDetallePedido">Guardar</button>
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

            function showLightbox(src) {
                document.getElementById('lightbox-img').src = src;
                document.getElementById('lightbox').style.display = 'flex';
            }
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Livewire.on('reset-select-cliente', () => {
                    const selectCliente = document.getElementById('select-cliente');
                    if (selectCliente && selectCliente.tomselect) {
                        selectCliente.tomselect.clear();
                    }

                    const selectCodigoEmpaque = document.getElementById('select-codigo-empaque');
                    if (selectCodigoEmpaque && selectCodigoEmpaque.tomselect) {
                        selectCodigoEmpaque.tomselect.clear();
                    }
                });
            });
        </script>
    @endpush

</div>
