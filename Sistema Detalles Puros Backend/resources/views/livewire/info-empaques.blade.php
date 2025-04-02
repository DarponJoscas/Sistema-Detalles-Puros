<div>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
    <div class="d-inline-block m-3" style="z-index: -800; position: absolute;">
        <div>
            <div>
                <div class="row g-0">
                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="codigoEmpaque" wire:model="filtro_codigo_empaque" wire:change="filtrarEmpaque">
                            <option value="">Buscar código empaque</option>
                            @foreach ($empaques as $empaque)
                                <option value="{{ $empaque->codigo_empaque }}">{{ $empaque->codigo_empaque }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="codigoPuro" wire:model="filtro_codigo_puro" wire:change="filtrarEmpaque">
                            <option value="">Buscar código puro</option>
                            @foreach ($puros as $puro)
                                <option value="{{ $puro->codigo_puro }}">{{ $puro->codigo_puro }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 190px; flex: none;">
                        <select id="tipoEmpaque" wire:model="filtro_tipo_empaque" wire:change="filtrarEmpaque">
                            <option value="">Buscar tipo empaque</option>
                            @foreach ($tiposEmpaque as $tipo)
                                <option value="{{ $tipo->tipo_empaque }}">{{ $tipo->tipo_empaque }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-2">
                <div wire:loading.flex class="loading-overlay">
                    <div class="spinner-border text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <button class="btn btn-primary" wire:click="importEmpaque">Cargar Empaque</button>
            </div>

        <!-- Tabla de resultados -->
        <div>
            <div class="table-responsive text-center">
                <table id="tablaEmpaques" class="table mt-3 text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th>N°</th>
                            <th>Código Empaque</th>
                            <th>Código Puro</th>
                            <th>Presentación</th>
                            <th>Sampler</th>
                            <th>Tipo Empaque</th>
                            <th>Descripción</th>
                            <th>Anillo</th>
                            <th>Imagen Anillado</th>
                            <th>Sello</th>
                            <th>UPC</th>
                            <th>Código Caja</th>
                            <th>Imagen Caja</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($datosPaginados as $dato)
                            <tr class="{{ $dato['estado_empaque'] == 0 ? 'table-secondary text-muted' : '' }}">
                                <td>{{ $dato['id_empaque'] }}</td>
                                <td>{{ $dato['codigo_empaque'] }}</td>
                                <td>{{ $dato['codigo_puro'] }}</td>
                                <td>{{ $dato['presentacion_puro'] }}</td>
                                <td>{{ $dato['sampler'] }}</td>
                                <td>{{ $dato['tipo_empaque'] }}</td>
                                <td>{{ $dato['descripcion_empaque'] }}</td>
                                <td>{{ $dato['anillo'] }}</td>
                                <td>
                                    @if ($dato['imagen_anillado'])
                                        <img src="{{ asset('storage/' . $dato['imagen_anillado']) }}"
                                            alt="Imagen Anillado" width="100" height="100"
                                            class="d-block mx-auto rounded">
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $dato['sello'] }}</td>
                                <td>{{ $dato['upc'] }}</td>
                                <td>{{ $dato['codigo_caja'] }}</td>
                                <td>
                                    @if ($dato['imagen_caja'])
                                    <img src="{{ asset('storage/' . $dato['imagen_caja']) }}" alt="Imagen Caja"
                                        width="100" height="100" class="d-block mx-auto rounded">
                                @else
                                    <span class="text-muted">Sin Imagen</span>
                                @endif
                                </td>
                                <td>
                                    <span class="badge {{ $dato['estado_empaque'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $dato['estado_empaque'] == 1 ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($dato['estado_empaque'] == 1)
                                        <div class="d-inline-block mb-1">
                                            <button type="button" class="btn btn-warning"
                                                wire:click="editarEmpaque('{{ $dato['codigo_empaque'] }}')">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </button>
                                        </div>
                                        <div class="d-inline-block mb-1">
                                            <button type="button" class="btn btn-danger"
                                                wire:click="eliminarEmpaque('{{ $dato['codigo_empaque'] }}')"
                                                onclick="return confirm('¿Está seguro de desactivar este empaque?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-success"
                                            wire:click="reactivarEmpaque('{{ $dato['codigo_empaque'] }}')"
                                            onclick="return confirm('¿Está seguro de reactivar este empaque?')">
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
    </div>

</div>
