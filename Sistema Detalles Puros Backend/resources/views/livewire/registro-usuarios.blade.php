<div>
    <!-- Vista que muestra los usuarios -->
    <div class="d-inline-block m-3">
        <div class="row">
            <div class="col-md-4">
                <label for="usuario">Buscar usuario:</label>
                <select type="text" id="usuario">
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id_usuario }}">{{ $usuario->name_usuario }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="roles">Buscar rol:</label>
                <select id="roles">
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $rol)
                    <option value="{{ $rol->id_rol }}">{{ $rol->rol }}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="mt-2">
            <div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#registrarusuarioModal">
                        Registrar Usuario
                    </button>
                </div>
                <div class="table-responsive text-center">
                    <table class="table mt-3 text-center align-middle">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 5%">N°</th>
                                <th style="width: 10%">Usuario</th>
                                <th style="width: 10%">Rol</th>
                                <th style="width: 10%">Estado</th>
                                <th style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($datosPaginados as $dato)
                                <tr class="{{ $dato['estado_usuario'] == 0 ? 'table-secondary text-muted' : '' }}">
                                    <td>
                                        {{ ($datosPaginados->currentPage() - 1) * $datosPaginados->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $dato['name_usuario'] }}
                                    </td>
                                    <td>
                                        {{ $dato['rol'] }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $dato['estado_usuario'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $dato['estado_usuario'] == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($dato['estado_usuario'] == 1)
                                            <button type="button" class="btn btn-warning"
                                                wire:click="editUser({{ $dato['id_usuario'] }})"
                                                data-id_usuario="{{ $dato['id_usuario'] }}">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </button>
                                            <div class="d-inline-block mb-1">
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="deleteUsuario({{ $dato['id_usuario'] }})"
                                                    onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="d-inline-block mb-1">
                                                <button type="button" class="btn btn-primary"
                                                    wire:click="updatePassword({{ $dato['id_usuario'] }})">
                                                    <i class="bi bi-key"></i>
                                                </button>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-success"
                                                wire:click="reactivarUsuario({{ $dato['id_usuario'] }})"
                                                onclick="return confirm('¿Está seguro de reactivar este usuario?')">
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


            <!-- Modal de registro de usuario -->
            <div class="modal fade" id="registrarusuarioModal" tabindex="-1" aria-labelledby="registerUserModalLabel"
                wire:ignore>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="registerUserModalLabel">Crear Nuevo Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:ignore>
                                <div class="form-group mb-3">
                                    <label for="register-usuario">Usuario:</label>
                                    <input type="text" wire:model="name_usuario" id="register-usuario"
                                        class="form-control" placeholder="Ingrese su usuario">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="register-contrasena-usuario">Contraseña:</label>
                                    <input type="password" wire:model="contrasena_usuario"
                                        id="register-contrasena-usuario" class="form-control"
                                        placeholder="Ingrese su contraseña">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="register-rol">Rol de Usuario:</label>
                                    <select wire:model="id_rol" id="register-rol" class="form-control">
                                        <option value="">Seleccione un rol</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id_rol }}">{{ $rol->rol }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" wire:click="register">Guardar
                                Usuario</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de actualizar un usuario -->
            <div class="modal fade" id="actualizarusuarioModal" tabindex="-1" aria-labelledby="updateUserModalLabel"
                aria-hidden="true" wire:ignore>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateUserModalLabel">Actualizar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group mb-3">
                                    <label for="update-usuario">Usuario:</label>
                                    <input type="text" wire:model="name_usuario" id="update-usuario"
                                        class="form-control" placeholder="Ingrese su usuario">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="update-rol">Rol de Usuario:</label>
                                    <select wire:model="rol" id="update-rol" class="form-control">
                                        <option value="">Seleccione un rol</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id_rol }}">{{ $rol->rol }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" wire:click="update">Actualizar
                                        Usuario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de actualizar contraseña -->
            <div class="modal fade" id="actualizarContrasenaModal" tabindex="-1"
                aria-labelledby="updatePasswordModalLabel" wire:ignore>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updatePasswordModalLabel">Actualizar Contraseña de Usuario
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:ignore>
                                <div class="form-group mb-3">
                                    <label for="update-contrasena-nueva">Nueva Contraseña:</label>
                                    <input type="password" wire:model="contrasena_usuario"
                                        id="update-contrasena-nueva" class="form-control"
                                        placeholder="Ingrese su nueva contraseña">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="update-contrasena-confirm">Confirmar Contraseña:</label>
                                    <input type="password" wire:model="contrasena_usuario"
                                        id="update-contrasena-confirm" class="form-control"
                                        placeholder="Confirma la nueva la contraseña">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" wire:click="updatePassword">Guardar
                                Usuario</button>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        Livewire.on('open-modal', () => {
                            const modal = new bootstrap.Modal(document.getElementById('actualizarusuarioModal'));
                            modal.show();
                        });

                        Livewire.on('open-modal-password', () => {
                            const modal = new bootstrap.Modal(document.getElementById('actualizarContrasenaModal'));
                            modal.show();
                        });

                    });
                </script>
            @endpush
        </div>
