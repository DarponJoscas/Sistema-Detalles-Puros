<div>
    @if(request()->is('registrarpuro'))
    <div class="modal fade" id="registrarpuroModal" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registrar Nuevo Puro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Código -->
                        <div class="form-group mb-3">
                            <label for="register-codigo">Código:</label>
                            <input type="text" wire:model.defer="codigo_puro" id="register-codigo" class="form-control" placeholder="Ingrese el código">
                            @error('codigo_puro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Presentación -->
                        <div class="form-group mb-3">
                            <label for="register-presentacion_puro">Presentación:</label>
                            <input type="text" wire:model.defer="presentacion_puro" id="register-presentacion_puro" class="form-control" placeholder="Ingrese la presentación" list="presentacion_puro-list">
                            <datalist id="presentacion_puro-list">
                                @foreach($presentaciones as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('presentacion_puro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Marca -->
                        <div class="form-group mb-3">
                            <label for="register-marca_puro">Marca:</label>
                            <input type="text" wire:model.defer="marca_puro" id="register-marca_puro" class="form-control" placeholder="Ingrese la marca" list="marca_puro-list">
                            <datalist id="marca_puro-list">
                                @foreach($marcas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('marca_puro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Vitola -->
                        <div class="form-group mb-3">
                            <label for="register-vitola">Vitola:</label>
                            <input type="text" wire:model.defer="vitola" id="register-vitola" class="form-control" placeholder="Ingrese la vitola" list="vitola-list">
                            <datalist id="vitola-list">
                                @foreach($vitolas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('vitola') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alias Vitola -->
                        <div class="form-group mb-3">
                            <label for="register-alias_vitola">Alias Vitola:</label>
                            <input type="text" wire:model.defer="alias_vitola" id="register-alias_vitola" class="form-control" placeholder="Ingrese el alias de la vitola" list="alias_vitola-list">
                            <datalist id="alias_vitola-list">
                                @foreach($alias_vitolas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('alias_vitola') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Capa -->
                        <div class="form-group mb-3">
                            <label for="register-capa_puro">Capa:</label>
                            <input type="text" wire:model.defer="capa_puro" id="register-capa_puro" class="form-control" placeholder="Ingrese la capa" list="capa_puro-list">
                            <datalist id="capa_puro-list">
                                @foreach($capas as $option)
                                    <option value="{{ $option }}">
                                @endforeach
                            </datalist>
                            @error('capa_puro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botón Guardar -->
                        <div class="form-group mb-3">
                            <button type="button" wire:click="createPuro" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
