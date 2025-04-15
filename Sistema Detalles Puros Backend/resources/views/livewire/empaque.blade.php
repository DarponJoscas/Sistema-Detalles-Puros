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

                <div wire:ignore class="col px-1" style="width: 240px; flex: none;">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPresentacion"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Buscar Presentación Puro
                        </button>
                        <div class="dropdown-menu p-2" aria-labelledby="dropdownPresentacion"
                            style="max-height: 300px; overflow-y: auto;">
                            @foreach ($presentaciones as $presentacion)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        value="{{ $presentacion->presentacion_puro }}"
                                        id="presentacion-{{ $loop->index }}" wire:model="filtro_presentacion"
                                        wire:change="filtrarPedidos">
                                    <label class="form-check-label" for="presentacion-{{ $loop->index }}">
                                        {{ $presentacion->presentacion_puro }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
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
                            <th>Código Empaque</th>
                            <th>Sampler</th>
                            <th>Descripción Empaque</th>
                            <th>Anillo</th>
                            <th>Sello</th>
                            <th>UPC</th>
                            <th>Tipo Empaque</th>
                            <th>Código Caja</th>
                            <th>Imagen Empaque</th>
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
                                <td>{{ $dato['codigo_empaque'] }}</td>
                                <td>{{ $dato['sampler'] }}</td>
                                <td>{{ $dato['descripcion_empaque'] }}</td>
                                <td>{{ $dato['anillo'] }}</td>
                                <td>{{ $dato['sello'] }}</td>
                                <td>{{ $dato['upc'] }}</td>
                                <td>{{ $dato['tipo_empaque'] }}</td>
                                <td>{{ $dato['codigo_caja'] }}</td>

                                <td>
                                    @php
                                        $imagenes = [];
                                        if (!empty($dato['imagen_anillado']) && is_array($dato['imagen_anillado'])) {
                                            $imagenes = array_merge($imagenes, $dato['imagen_anillado']);
                                        }
                                        if (!empty($dato['imagen_caja']) && is_array($dato['imagen_caja'])) {
                                            $imagenes = array_merge($imagenes, $dato['imagen_caja']);
                                        }
                                    @endphp

                                    @if (!empty($imagenes))
                                        <div
                                            style="display: grid; grid-template-columns: repeat(3, 80px); gap: 6px; padding: 0; margin: 0; justify-items: center; align-items: center; width: 100%;">

                                            @foreach ($imagenes as $img)
                                                <div style="width: 80px; height: 80px;">
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Imagen"
                                                        width="80" height="80" style="display: block; margin: 0; cursor:pointer"
                                                        onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td><span
                                        class="badge {{ $dato['estado_pedido'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $dato['estado_pedido'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span></td>
                                <td>
                                    @if ($dato['estado_pedido'] == 1)
                                        <div class="d-inline-block m-1">
                                            <button type="button" class="btn btn-warning"
                                                wire:click="editarPedido({{ $dato['id_pedido'] }})"
                                                onclick="document.getElementById('select-cliente').tomselect.setValue({{ $dato['id_cliente'] }});document.getElementById('select-codigo-empaque').tomselect.setValue({{ $dato['codigo_empaque'] }})">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </button>
                                        </div>
                                    @else
                                    @endif
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
                            <input type="text" class="form-control" id="cliente"
                                value="{{ optional($clientes->firstWhere('id_cliente', $id_cliente))->name_cliente }}"
                                readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="codigo_empaque">Código de Empaque:</label>
                            <input type="text" class="form-control" id="codigo_empaque"
                                value="{{ optional($empaques->firstWhere('codigo_empaque', $codigo_empaque))->codigo_empaque }}"
                                readonly>
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
                            <label for="imagen_anillado">Imágenes de Anillado (máx. 3):</label>
                            <input type="file" wire:model="imagen_anillado_nuevas" id="imagen_anillado"
                                class="form-control" multiple accept="image/*">

                            @if ($imagen_anillado || $imagen_anillado_nuevas)
                                <div class="mt-2 d-flex flex-wrap gap-2">

                                    @foreach ($imagen_anillado as $index => $imagen)
                                        <div>
                                            <img src="{{ asset('storage/' . $imagen) }}" class="img-thumbnail"
                                                width="150">
                                            <p class="text-center">Imagen guardada {{ $index + 1 }}</p>
                                            <button type="button"
                                                wire:click="eliminarImagenExistente('anillado', {{ $index }})"
                                                class="btn btn-sm btn-danger mt-1">Eliminar</button>
                                        </div>
                                    @endforeach

                                    @foreach ($imagen_anillado_nuevas as $index => $imagen)
                                        <div>
                                            <img src="{{ $imagen->temporaryUrl() }}" class="img-thumbnail"
                                                width="150">
                                            <p class="text-center">Nueva Imagen {{ $index + 1 }}</p>
                                        </div>
                                    @endforeach
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
                            <label for="imagen_caja">Imágenes de Caja (máx. 3):</label>
                            <input type="file" wire:model="imagen_caja_nuevas" id="imagen_caja"
                                class="form-control" multiple accept="image/*">

                            @if ($imagen_caja || $imagen_caja_nuevas)
                                <div class="mt-2 d-flex flex-wrap gap-2">

                                    @foreach ($imagen_caja as $index => $imagen)
                                        @if (is_string($imagen))
                                            <div>
                                                <img src="{{ asset('storage/' . $imagen) }}" class="img-thumbnail"
                                                    width="150">
                                                <p class="text-center">Imagen guardada {{ $index + 1 }}</p>
                                                <button type="button"
                                                    wire:click="eliminarImagenExistente('caja', {{ $index }})"
                                                    class="btn btn-sm btn-danger mt-1">Eliminar</button>
                                            </div>
                                        @endif
                                    @endforeach

                                    @foreach ($imagen_caja_nuevas as $index => $imagen)
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
