{{-- resources/views/livewire/administracion.blade.php --}}
<div class="d-inline-block m-3">
    <div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarpedidoModal">
                Registrar Nuevo Pedido
            </button>
        </div>

        <div class="table-responsive text-center">
            <table class="table mt-3 mx-auto text-center align-middle">
                <thead class="text-center">
                    <tr>
                        <th>N° Pedido</th>
                        <th>Cliente</th>
                        <th>Código Puro</th>
                        <th>Presentación Puro</th>
                        <th>Marca Puro</th>
                        <th>Alias Vitola</th>
                        <th>Vitola</th>
                        <th>Capa Puro</th>
                        <th>Descripción Producción</th>
                        <th>Imagen Producción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($datosPaginados as $dato)
                        <tr>
                            <td>{{ $dato->id_pedido }}</td>
                            <td>{{ $dato->cliente->name_cliente }}</td>
                            <td>{{ $dato->puro->codigo_puro }}</td>
                            <td>{{ $dato->puro->presentacion }}</td>
                            <td>{{ $dato->puro->marca }}</td>
                            <td>{{ $dato->puro->alias_vitola }}</td>
                            <td>{{ $dato->puro->vitola }}</td>
                            <td>{{ $dato->puro->capa }}</td>
                            <td>{{ $dato->descripcion_produccion }}</td>
                            <td>
                                @if($dato->imagen_produccion)
                                <img src="{{ asset('storage/' . $dato->imagen_produccion) }}" alt="Imagen Producción" width="200" height="200" class="d-block mx-auto">
                                @else
                                <span class="text-muted">Sin Imagen</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-inline-block m-1">
                                    <button type="button" class="btn btn-warning" wire:click="cargarPedido({{ $dato->id_pedido }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                <div class="d-inline-block m-1">
                                    <button type="button" class="btn btn-danger" wire:click="eliminarPedido({{ $dato->id_pedido }})">
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
</div>
