<div>
    <div class="d-inline-block m-3">
        <div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#registrarpuroModal">
                    Registrar Nuevo Pedido
                </button>
                <button class="btn btn-success" wire:click="filtrarPuros(1)">Puros Activos</button>
                <button class="btn btn-danger" wire:click="filtrarPuros(0)">Puros Inactivos</button>
                <button class="btn btn-secondary" wire:click="filtrarPuros(null)">Mostrar Todos</button>
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
            <div class="table-responsive text-center">
                <table class="table mt-3 text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th>N°</th>
                            <th>Código Puro</th>
                            <th>Presentación Puro</th>
                            <th>Marca Puro</th>
                            <th>Vitola</th>
                            <th>Alias Vitola</th>
                            <th>Capa Puro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr>
                                <td >{{''}}</td>
                                <td style="width: 15%;">{{ $dato['codigo_puro'] }}</td>
                                <td style="width: 15%;">{{ $dato['presentacion_puro'] }}</td>
                                <td style="width: 15%;">{{ $dato['marca_puro'] }}</td>
                                <td style="width: 15%;">{{ $dato['vitola'] }}</td>
                                <td style="width: 15%;">{{ $dato['alias_vitola'] }}</td>
                                <td style="width: 15%;">{{ $dato['capa_puro'] }}</td>
                                <td>

                                    <div class="d-inline-block m-1">
                                        <button type="button" class="btn btn-warning" wire:click="">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </div>
                                    <div class="d-inline-block m-1">
                                        <button type="button" class="btn btn-danger" wire:click="eliminarPuros({{ $dato['codigo_puro'] }})">
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

    <!-- Modal para registrar un puro-->
    <div class="modal fade" id="registrarpuroModal" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registrar Nuevo Puro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="register-codigo">Código:</label>
                            <input type="text" wire:model.defer="codigo_puro" id="register-codigo"
                                class="form-control" placeholder="Ingrese el código">
                            @error('codigo_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-presentacion_puro">Presentación:</label>
                            <input type="text" wire:model.defer="presentacion_puro" id="register-presentacion_puro"
                                class="form-control" placeholder="Ingrese la presentación"
                                list="presentacion_puro-list">
                            <datalist id="presentacion_puro-list">
                                @foreach ($presentaciones as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('presentacion_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-marca_puro">Marca:</label>
                            <input type="text" wire:model.defer="marca_puro" id="register-marca_puro"
                                class="form-control" placeholder="Ingrese la marca" list="marca_puro-list">
                            <datalist id="marca_puro-list">
                                @foreach ($marcas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('marca_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-vitola">Vitola:</label>
                            <input type="text" wire:model.defer="vitola" id="register-vitola" class="form-control"
                                placeholder="Ingrese la vitola" list="vitola-list">
                            <datalist id="vitola-list">
                                @foreach ($vitolas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('vitola')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-alias_vitola">Alias Vitola:</label>
                            <input type="text" wire:model.defer="alias_vitola" id="register-alias_vitola"
                                class="form-control" placeholder="Ingrese el alias de la vitola"
                                list="alias_vitola-list">
                            <datalist id="alias_vitola-list">
                                @foreach ($alias_vitolas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('alias_vitola')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="register-capa_puro">Capa:</label>
                            <input type="text" wire:model.defer="capa_puro" id="register-capa_puro"
                                class="form-control" placeholder="Ingrese la capa" list="capa_puro-list">
                            <datalist id="capa_puro-list">
                                @foreach ($capas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('capa_puro')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <button type="button" wire:click="createPuro" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.addEventListener('openModal', event => {
                    let modalId = event.detail.modalId;
                    let modal = new bootstrap.Modal(document.getElementById(modalId));
                    modal.show();
                });

                window.addEventListener('closeModal', event => {
                    let modalId = event.detail.modalId;
                    let modalElement = document.getElementById(modalId);
                    let modal = bootstrap.Modal.getInstance(modalElement);
                    modal.hide();
                });

                document.addEventListener('close-modal', () => {
                    Livewire.emit('cerrarModal');
                });
            });
        </script>
    @endpush
</div>
