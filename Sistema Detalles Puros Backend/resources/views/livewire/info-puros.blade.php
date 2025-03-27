<div>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
    <div class="d-inline-block m-3" style="z-index: -800; position: absolute;">
        <div>
            <div>
                <div class="row g-0">
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
            </div>
            <div class="d-flex justify-content-between mt-2">
                <div wire:loading.flex class="loading-overlay">
                    <div class="spinner-border text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <button class="btn btn-primary" wire:click="importProducts" wire:loading.remove>
                    Cargar Puros
                </button>
            </div>
            <div class="mt-3">
                @if ($processedCount > 0)
                    <p>Se han procesado <strong>{{ $processedCount }}</strong> de
                        <strong>{{ $totalCount }}</strong>
                        registros.
                    </p>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabla de resultados -->
        <div>
            <div class="table-responsive text-center">
                <table id="tablaPuros" class="table mt-3 text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%">N°</th>
                            <th style="width: 10%">Código Puro</th>
                            <th style="width: 10%">Presentación</th>
                            <th style="width: 10%">Marca</th>
                            <th style="width: 10%">Vitola</th>
                            <th style="width: 10%">Alias Vitola</th>
                            <th style="width: 10%">Capa</th>
                            <th style="width: 10%">Estado</th>
                            <th style="width: 10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr class="{{ $dato['estado_puro'] == 0 ? 'table-secondary text-muted' : '' }}">
                                <td style="width: 5%">
                                    {{ ($datosPaginados->currentPage() - 1) * $datosPaginados->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $dato['codigo_puro'] }}</td>
                                <td>{{ $dato['presentacion_puro'] }}</td>
                                <td>{{ $dato['marca'] }}</td>
                                <td>{{ $dato['vitola'] }}</td>
                                <td>{{ $dato['alias_vitola'] }}</td>
                                <td>{{ $dato['capa'] }}</td>
                                <td>
                                    <span class="badge {{ $dato['estado_puro'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $dato['estado_puro'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($dato['estado_puro'] == 1)
                                        <div class="d-inline-block mb-1">
                                            <button type="button" class="btn btn-warning"
                                                wire:click="editPuro('{{ $dato['codigo_puro'] }}')">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </button>
                                        </div>
                                        <div class="d-inline-block mb-1">
                                            <button type="button" class="btn btn-danger"
                                                wire:click="eliminarPuros('{{ $dato['codigo_puro'] }}')"
                                                onclick="return confirm('¿Está seguro de desactivar este puro?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-success"
                                            wire:click="reactivarPuro('{{ $dato['codigo_puro'] }}')"
                                            onclick="return confirm('¿Está seguro de reactivar este puro?')">
                                            <i class="bi bi-arrow-clockwise"></i> Reactivar
                                        </button>
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

    <!-- Modal para actualizar un puro-->
    <div class="modal fade" id="actualizarpuroModal" tabindex="-1" aria-labelledby="modalLabel" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        Editar Puro
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form wire:ignore>
                        <div class="form-group mb-3">
                            <label for="register-codigo">Código Puro:</label>
                            <input type="text" wire:model.defer="codigo_puro" id="register-codigo"
                                class="form-control" placeholder="Ingrese el código">

                        </div>


                        <div class="form-group mb-3">
                            <label for="register-presentacion_puro">Presentación:</label>
                            <input type="text" wire:model.lazy="presentacion_puro" id="register-presentacion_puro"
                                class="form-control" placeholder="Ingrese la presentación"
                                list="presentacion_puro-list">
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-marca_puro">Marca:</label>
                            <input type="text" wire:model.defer="marca_puro" id="register-marca_puro"
                                class="form-control" placeholder="Ingrese la marca" list="marca_puro-list">
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-vitola">Vitola:</label>
                            <input type="text" wire:model.defer="vitola" id="register-vitola"
                                class="form-control" placeholder="Ingrese la vitola">

                        </div>

                        <div class="form-group mb-3">
                            <label for="register-alias_vitola">Alias Vitola:</label>
                            <input type="text" wire:model.defer="alias_vitola" id="register-alias_vitola"
                                class="form-control" placeholder="Ingrese el alias de la vitola">

                        </div>

                        <div class="form-group mb-3">
                            <label for="register-capa">Capa Puro:</label>
                            <input type="text" wire:model.defer="capa" id="register-capa" class="form-control"
                                placeholder="Ingrese la capa">

                        </div>
                    </form>

                    <div div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" wire:click="updatePuro" class="btn btn-primary">
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                Livewire.on('open-modal', () => {
                    const modal = new bootstrap.Modal(document.getElementById('actualizarpuroModal'));
                    modal.show();
                });

            });
        </script>
    @endpush
</div>
