<div>

    @if(request()->is('registrarcliente'))
    <!-- Modal de registrar un cliente-->
    <div class="modal fade" id="RegistrarClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="register-cliente">Cliente:</label>
                            <input type="text" wire:model.defer="cliente" id="register-cliente" class="form-control" placeholder="Ingrese el nuevo cliente">
                            @error('cliente') <span class="error-message text-danger">{{ $message }}</span> @enderror
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="register">Guardar Cliente</button>
                </div>
            </div>
        </div>
    </div>

    @elseif(request()->is('modificarcliente'))
    <!-- Modal de modificar un cliente-->
    <div class="modal fade" id="ModificarClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="register-cliente">Cliente:</label>
                            <input type="text" wire:model.defer="cliente" id="register-cliente" class="form-control" placeholder="Ingrese el nuevo cliente">
                            @error('cliente') <span class="error-message text-danger">{{ $message }}</span> @enderror
                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="register">Guardar Cliente</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
