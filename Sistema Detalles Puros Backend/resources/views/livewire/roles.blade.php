<div>
    @if(request()->is('createrol'))
    <div>
        <div class="modal fade" id="createrolModal" tabindex="-1" aria-labelledby="exampleModalLabel" wire:ignore>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group mb-3">
                                    <label for="register-usuario">Rol:</label>
                                    <input type="text" wire:model.defer="rol" id="register-usuario" class="form-control" placeholder="Ingrese su usuario">
                                    @error('rol') <span class="error-message text-danger">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="register">Guardar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif(request()->is('updaterol'))
    <div>
        <div class="modal fade" id="updaterolModal" tabindex="-1" aria-labelledby="exampleModalLabel" wire:ignore>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Actualziar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group mb-3">
                                    <label for="register-usuario">Rol:</label>
                                    <input type="text" wire:model.defer="rol" id="register-usuario" class="form-control" placeholder="Ingrese su usuario">
                                    @error('rol') <span class="error-message text-danger">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="register">Guardar Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
