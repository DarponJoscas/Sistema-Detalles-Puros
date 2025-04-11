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

        .lightbox {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }
    </style>
    <div class="d-inline-block m-3" style="z-index: -800; position: absolute;">
        <div>


            <!-- Tabla de resultados -->
            <div>

                <div class="row g-0">
                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="codigoPuro" wire:model="filtro_codigo_puro" wire:change="filtrarPedidos">
                            <option value="">Buscar un código puro</option>
                            @foreach ($puros as $puro)
                                <option value="{{ $puro->codigo_puro }}">{{ $puro->codigo_puro }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div  wire:ignore class="col px-1" style="width: 240px; flex: none;">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPresentacion" data-bs-toggle="dropdown" aria-expanded="false">
                                Buscar Presentación Puro
                            </button>
                            <div class="dropdown-menu p-2" aria-labelledby="dropdownPresentacion" style="max-height: 300px; overflow-y: auto;">
                                @foreach ($presentaciones as $presentacion)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               value="{{ $presentacion->presentacion_puro }}"
                                               id="presentacion-{{ $loop->index }}"
                                               wire:model="filtro_presentacion"
                                               wire:change="filtrarPedidos">
                                        <label class="form-check-label" for="presentacion-{{ $loop->index }}">
                                            {{ $presentacion->presentacion_puro }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="marca" wire:model="filtro_marca" wire:change="filtrarPedidos">
                            <option value="">Buscar marca</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->marca }}">{{ $marca->marca }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="vitola" wire:model="filtro_vitola" wire:change="filtrarPedidos">
                            <option value="">Buscar vitola</option>
                            @foreach ($vitolas as $vitola)
                                <option value="{{ $vitola->vitola }}">{{ $vitola->vitola }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="aliasVitola" wire:model="filtro_alias_vitola" wire:change="filtrarPedidos">
                            <option value="">Buscar alias vitola</option>
                            @foreach ($alias_vitolas as $alias_vitola)
                                <option value="{{ $alias_vitola->alias_vitola }}">{{ $alias_vitola->alias_vitola }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="capa" wire:model="filtro_capa" wire:change="filtrarPedidos">
                            <option value="">Buscar capa</option>
                            @foreach ($capas as $capa)
                                <option value="{{ $capa->capa }}">{{ $capa->capa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="codigoItem" wire:model="filtro_codigo_empaque" wire:change="filtrarPedidos">
                            <option value="">Buscar item</option>
                            @foreach ($empaques as $empaque)
                                <option value="{{ $empaque->codigo_empaque }}">{{ $empaque->codigo_empaque }}</option>
                            @endforeach
                        </select>
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
                                        @if (!empty($dato['imagen_anillado']) && is_array($dato['imagen_anillado']))
                                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                                @foreach ($dato['imagen_anillado'] as $img)
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Imagen anillado"
                                                        width="80" height="80" class="rounded"
                                                        style="cursor:pointer"
                                                        onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">Sin Imagen</span>
                                        @endif

                                        <div id="lightbox" class="lightbox" onclick="this.style.display='none'">
                                            <img id="lightbox-img" src="">
                                        </div>
                                    </td>
                                    <td>{{ $dato['sello'] }}</td>
                                    <td>{{ $dato['upc'] }}</td>
                                    <td>{{ $dato['codigo_caja'] }}</td>
                                    <td>
                                        @if (!empty($dato['imagen_caja']) && is_array($dato['imagen_caja']))
                                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                                @foreach ($dato['imagen_caja'] as $img)
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Imagen Caja"
                                                        width="80" height="80" class="rounded"
                                                        style="cursor:pointer"
                                                        onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">Sin Imagen</span>
                                        @endif

                                        <div id="lightbox" class="lightbox" onclick="this.style.display='none'">
                                            <img id="lightbox-img" src="">
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $dato['estado_empaque'] == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $dato['estado_empaque'] == 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $datosPaginados->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function showLightbox(src) {
                    document.getElementById('lightbox-img').src = src;
                    document.getElementById('lightbox').style.display = 'flex';
                }
            </script>
        @endpush
    </div>
