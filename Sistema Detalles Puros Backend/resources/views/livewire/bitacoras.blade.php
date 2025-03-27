<div>
    <div class="d-inline-block m-3" style="z-index: -800; position:absolute;">
        <div class="row g-0">
            <div wire:ignore class="col px-1" style="width: 180px; flex: none;">
                <select id="filtroDescripcion" wire:model="filtro_descripcion" wire:change="filtrarBitacora">
                    <option value="">Seleccione una descripcion</option>
                    @foreach ($descripciones as $descripcion)
                        <option value="{{ $descripcion->descripcion }}">{{ $descripcion->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="filtroAccion" wire:model="filtro_accion" wire:change="filtrarBitacora">
                    <option value="">Seleccione una accion</option>
                    @foreach ($acciones as $accion)
                        <option value="{{ $accion->accion }}">{{ $accion->accion }}</option>
                    @endforeach
                </select>
            </div>
            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="filtroUsuarios" wire:model="filtro_usuario" wire:change="filtrarBitacora">
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->name_usuario }}">{{ $usuario->name_usuario }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <div class="table-responsive text-center">
                <table class="table text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%">N°</th>
                            <th style="width: 10%">Descripción</th>
                            <th style="width: 10%">Acción</th>
                            <th style="width: 10%">Usuario</th>
                            <th style="width: 10%">Fecha de Creación</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr>
                                <td>
                                    {{ ($datosPaginados->currentPage() - 1) * $datosPaginados->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $dato['descripcion'] }}</td>
                                <td>{{ $dato['accion'] }}</td>
                                <td>{{ $dato['usuario'] }}</td>
                                <td>{{ $dato['fecha_creacion'] }}</td>
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

    @push('scripts')
        <script>
            new TomSelect('#filtroUsuarios');
            new TomSelect('#filtroAccion');
            new TomSelect('#filtroDescripcion');
        </script>
    @endpush
</div>
