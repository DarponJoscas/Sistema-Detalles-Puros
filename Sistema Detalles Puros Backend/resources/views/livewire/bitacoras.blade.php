<div>
    <div class="d-inline-block m-1" >
        <div class="row g-2">
            <div wire:ignore class="col px-1" style="z-index: -800; position:absolute;">
                <select id="filtroUsuarios" wire:model="filtro_usuario" wire:change="filtrarBitacora">
                    <option value="">Seleccione un usuario</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->name_usuario }}">{{ $usuario->name_usuario }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive text-center mt-5" style="z-index: -1000; position:absolute;">
        <table class="table text-center align-middle">
            <thead class="text-center">
                <tr>
                    <th>N°</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                    <th>Usuario</th>
                    <th>Fecha de Creación</th>
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

    @push('scripts')
    <script>
        new TomSelect('#filtroUsuarios');
    </script>
    @endpush
</div>
