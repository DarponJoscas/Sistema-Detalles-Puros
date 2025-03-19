<div class="container">
    <div class="row">
        <!-- Primera columna: Tabla de Clientes -->
        <div class="col-md-4">
            <div class="card m-3">
                <div class="table-responsive text-center">
                    <table class="table mt-3 text-center align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>N°</th>
                                <th>Nombre del Cliente</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ ($clientes->currentPage() - 1) * $clientes->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $cliente['name_cliente'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning"
                                            wire:click="editCliente({{ $cliente['id_cliente'] }})">
                                            <i class="bi bi-pencil-square text-white"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="deleteCliente({{ $cliente['id_cliente'] }})"
                                            onclick="return confirm('¿Está seguro de eliminar este cliente?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $this->clientes->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card m-3">
                <div class="table-responsive text-center">
                    <table class="table mt-3 text-center align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>N°</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($roles as $rol)
                                <tr>
                                    <td>
                                        {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $rol['rol'] }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning"
                                            wire:click="editRol({{ $rol['id_rol'] }})">
                                            <i class="bi bi-pencil-square text-white"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="deleteRol({{ $rol['id_rol'] }})"
                                            onclick="return confirm('¿Está seguro de eliminar este rol?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $this->roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
