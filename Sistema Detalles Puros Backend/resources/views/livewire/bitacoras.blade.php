<div>
    <div class="d-inline-block m-3" style="z-index: -800; position:absolute;">
        <div class="row g-0">

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

            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="codigoPuro" wire:model="filtro_codigo_puro" wire:change="filtrarBitacora">
                    <option value="">Buscar un código puro</option>
                    @foreach ($puros as $puro)
                        <option value="{{ $puro->codigo_puro }}">{{ $puro->codigo_puro }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col px-1" style="width: 240px; flex: none;">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPresentacion"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Buscar Presentación Puro
                    </button>
                    <div class="dropdown-menu p-2" aria-labelledby="dropdownPresentacion"
                        style="max-height: 300px; overflow-y: auto;">
                        @foreach ($presentaciones as $presentacion)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    value="{{ $presentacion->presentacion_puro }}"
                                    id="presentacion-{{ $loop->index }}" wire:model="filtro_presentacion"
                                    wire:change="filtrarBitacora">
                                <label class="form-check-label" for="presentacion-{{ $loop->index }}">
                                    {{ $presentacion->presentacion_puro }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="marca" wire:model="filtro_marca" wire:change="filtrarBitacora">
                    <option value="">Buscar marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->marca }}">{{ $marca->marca }}</option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="vitola" wire:model="filtro_vitola" wire:change="filtrarBitacora">
                    <option value="">Buscar vitola</option>
                    @foreach ($vitolas as $vitola)
                        <option value="{{ $vitola->vitola }}">{{ $vitola->vitola }}</option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="aliasVitola" wire:model="filtro_alias_vitola" wire:change="filtrarBitacora">
                    <option value="">Buscar alias vitola</option>
                    @foreach ($alias_vitolas as $alias_vitola)
                        <option value="{{ $alias_vitola->alias_vitola }}">{{ $alias_vitola->alias_vitola }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                <select id="capa" wire:model="filtro_capa" wire:change="filtrarBitacora">
                    <option value="">Buscar capa</option>
                    @foreach ($capas as $capa)
                        <option value="{{ $capa->capa }}">{{ $capa->capa }}</option>
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
                            <th>Código Puro</th>
                            <th>Presentación</th>
                            <th>Marca</th>
                            <th>Vitola</th>
                            <th>Alias Vitola</th>
                            <th>Capa</th>
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
                                <td>{{ $dato['codigo_puro'] }}</td>
                                <td>{{ $dato['presentacion_puro'] }}</td>
                                <td>{{ $dato['marca'] }}</td>
                                <td>{{ $dato['vitola'] }}</td>
                                <td>{{ $dato['alias_vitola'] }}</td>
                                <td>{{ $dato['capa'] }}</td>
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
        </script>
    @endpush
</div>
