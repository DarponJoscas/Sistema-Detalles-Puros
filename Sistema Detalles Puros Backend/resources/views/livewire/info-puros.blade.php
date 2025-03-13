<div>
    <div class="d-inline-block m-3">
        <div>
            <button type="button" class="btn btn-primary" wire:click="openModal">Registrar Nuevo Puro</button>
            <button class="btn btn-success" wire:click="filtrarPuros(1)">Puros Activos</button>
            <button class="btn btn-danger" wire:click="filtrarPuros(0)">Puros Inactivos</button>
            <button class="btn btn-secondary" wire:click="filtrarPuros(null)">Mostrar Todos</button>
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
        <div class="table-responsive text-center">
            <table class="table mt-3 text-center align-middle">
                <thead class="text-center">
                    <tr>
                        <th>N°</th>
                        <th>Código Puro</th>
                        <th>Presentación</th>
                        <th>Marca</th>
                        <th>Vitola</th>
                        <th>Alias Vitola</th>
                        <th>Capa</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($datosPaginados as $dato)
                        <tr class="{{ $dato['estado_puro'] == 0 ? 'table-secondary text-muted' : '' }}">
                            <td style="width: 5%">
                                {{ ($datosPaginados->currentPage() - 1) * $datosPaginados->perPage() + $loop->iteration }}
                            </td>
                            <td style="width: 10%">{{ $dato['codigo_puro'] }}</td>
                            <td style="width: 10%">{{ $dato['presentacion_puro'] }}</td>
                            <td style="width: 10%">{{ $dato['marca'] }}</td>
                            <td style="width: 10%">{{ $dato['vitola'] }}</td>
                            <td style="width: 10%">{{ $dato['alias_vitola'] }}</td>
                            <td style="width: 10%">{{ $dato['capa'] }}</td>
                            <td style="width: 10%">
                                <span class="badge {{ $dato['estado_puro'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $dato['estado_puro'] == 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td style="width: 10%">
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

    <!-- Modal -->
    <div class="modal fade" id="registrarpuroModal" tabindex="-1" aria-labelledby="modalLabel" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        {{ $editing ? 'Editar Puro' : 'Registrar Nuevo Puro' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form wire:ignore>
                        <div class="form-group mb-3">
                            <label for="register-codigo">Código Puro:</label>
                            <input type="text" wire:model.defer="codigo_puro" id="register-codigo"
                                class="form-control" placeholder="Ingrese el código">
                            @error('codigo_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-presentacion_puro">Presentación:</label>
                            <input type="text" wire:model.defer="presentacion_puro" id="register-presentacion_puro"
                                placeholder="Ingrese la presentación">
                            @error('presentacion_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-marca">Marca:</label>
                            <input type="text" wire:model.defer="marca" id="register-marca"
                                placeholder="Ingrese la marca">
                            @error('marca')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-vitola">Vitola:</label>
                            <input type="text" wire:model.defer="vitola" id="register-vitola"
                                placeholder="Ingrese la vitola">
                            @error('vitola')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-alias_vitola">Alias Vitola:</label>
                            <input type="text" wire:model.defer="alias_vitola" id="register-alias_vitola"
                                placeholder="Ingrese el alias de la vitola">
                            @error('alias_vitola')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-capa">Capa Puro:</label>
                            <input type="text" wire:model.defer="capa" id="register-capa"
                                placeholder="Ingrese la capa">
                            @error('capa')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>

                    <div class="form-group mb-3">
                        <button type="button" wire:click="createPuro" class="btn btn-primary">
                            {{ $editing ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let tomSelectInstances = {};

            const selectConfigs = {
                'codigo_puro_select': {
                    instance: 'codigoPuro',
                    property: 'codigo_puro_busqueda',
                    options: @json($codigos_puros),
                    placeholder: 'Buscar por código puro'
                },
                'register-presentacion_puro': {
                    instance: 'presentacion',
                    property: 'presentacion_puro',
                    options: @json($presentaciones),
                    placeholder: 'Ingrese la presentación'
                },
                'register-marca': {
                    instance: 'marca',
                    property: 'marca',
                    options: @json($marcas),
                    placeholder: 'Ingrese la marca'
                },
                'register-vitola': {
                    instance: 'vitola',
                    property: 'vitola',
                    options: @json($vitolas),
                    placeholder: 'Ingrese la vitola'
                },
                'register-alias_vitola': {
                    instance: 'aliasVitola',
                    property: 'alias_vitola',
                    options: @json($alias_vitolas),
                    placeholder: 'Ingrese el alias de la vitola'
                },
                'register-capa': {
                    instance: 'capa',
                    property: 'capa',
                    options: @json($capas),
                    placeholder: 'Ingrese la capa'
                }
            };

            function destroyTomSelects() {
                Object.values(tomSelectInstances).forEach(instance => {
                    if (instance && typeof instance.destroy === 'function') {
                        instance.clear();
                        instance.destroy();
                    }
                });
                tomSelectInstances = {};
            }


            function initSelects() {
                Object.entries(selectConfigs).forEach(([elementId, config]) => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        tomSelectInstances[config.instance] = new TomSelect(`#${elementId}`, {
                            create: true,
                            createOnBlur: true,
                            persist: false,
                            maxItems: 1,
                            valueField: 'value',
                            labelField: 'text',
                            searchField: 'text',
                            options: config.options,
                            placeholder: config.placeholder,
                            onItemAdd: function(value) {
                                @this.set(config.property, value);

                                if (config.instance === 'codigoPuro' && value) {
                                    @this.call('buscarPorCodigo');
                                }
                            }
                        });

                        if (@this.editing && @this[config.property]) {
                            tomSelectInstances[config.instance].setValue(@this[config.property]);
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                window.Livewire.on('open-modal', () => {
                    const modal = new bootstrap.Modal(document.getElementById('registrarpuroModal'));
                    modal.show();
                    setTimeout(() => {
                        destroyTomSelects();
                        initSelects();
                    }, 200);
                });

                window.Livewire.on('close-modal', () => {
                    const modalElement = document.getElementById('registrarpuroModal');
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }


            window.Livewire.on('saved', () => {
                        closeModal(registrarpuroModal);
                        showAlert('success', 'Se ha registrado correctamente');
                    });

                    destroyTomSelects();

                    @this.set('presentacion_puro', '');
                    @this.set('marca', '');
                    @this.set('alias_vitola', '');
                    @this.set('vitola', '');
                    @this.set('capa', '');
                    @this.set('codigo_puro', '');
                    @this.set('editing', false);
                    @this.set('originalCodigo', '');
                });

                initSelects();
            });

            document.addEventListener('livewire:load', function() {
                Livewire.hook('message.processed', (message, component) => {
                    const modal = document.getElementById('registrarpuroModal');
                    if (modal && modal.classList.contains('show')) {
                        setTimeout(() => initSelects(), 100);
                    }
                });

                window.Livewire.on('reset-tom-select', () => {
                    destroyTomSelects();
                    initSelects();
                });
            });
        </script>
    @endpush
</div>
