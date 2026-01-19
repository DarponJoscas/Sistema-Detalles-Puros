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

                    <div wire:ignore class="col px-1" style="width: 240px; flex: none;">
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
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <div wire:loading.flex wire:target="importEmpaque" class="loading-overlay">
                        <div class="spinner-border text-dark" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <button class="btn btn-primary" wire:click="importEmpaque">Cargar Empaque</button>
                </div>

                <div>
                    <div class="table-responsive text-center">
                        <table id="tablaEmpaque" class="table mt-3 text-center align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 5%">N°</th>
                                    <th style="width: 10%">Código Empaque</th>
                                    <th style="width: 10%">Código Puro</th>
                                    <th style="width: 10%">Presentación</th>
                                    <th style="width: 10%">Marca</th>
                                    <th style="width: 10%">Vitola</th>
                                    <th style="width: 10%">Alias Vitola</th>
                                    <th style="width: 10%">Capa</th>
                                    <th style="width: 10%">Sampler</th>
                                    <th style="width: 10%">Tipo Empaque</th>
                                    <th style="width: 10%">Descripción</th>
                                    <th style="width: 10%">Anillo</th>
                                    <th style="width: 10%">Sello</th>
                                    <th style="width: 10%">UPC</th>
                                    <th style="width: 10%">Código Caja</th>
                                    <th style="width: 10%">Imagenes Empaque</th>
                                    <th style="width: 10%">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($datosPaginados as $dato)
                                    <tr class="{{ $dato['estado_empaque'] == 0 ? 'table-secondary text-muted' : '' }}">
                                        <td>{{ $dato['id_empaque'] }}</td>
                                        <td>{{ $dato['codigo_empaque'] }}</td>
                                        <td>{{ $dato['codigo_puro'] }}</td>
                                        <td>{{ $dato['presentacion_puro'] }}</td>
                                        <td>{{ $dato['marca'] }}</td>
                                        <td>{{ $dato['vitola'] }}</td>
                                        <td>{{ $dato['alias_vitola'] }}</td>
                                        <td>{{ $dato['capa'] }}</td>

                                        <td>{{ $dato['sampler'] }}</td>
                                        <td>{{ $dato['tipo_empaque'] }}</td>
                                        <td>{{ $dato['descripcion_empaque'] }}</td>
                                        <td>{{ $dato['anillo'] }}</td>
                                        <td>{{ $dato['sello'] }}</td>
                                        <td>{{ $dato['upc'] }}</td>
                                        <td>{{ $dato['codigo_caja'] }}</td>
                                        <td>
                                            @php
                                                $imagenes = [];
                                                if (
                                                    !empty($dato['imagen_anillado']) &&
                                                    is_array($dato['imagen_anillado'])
                                                ) {
                                                    $imagenes = array_merge($imagenes, $dato['imagen_anillado']);
                                                }
                                                if (!empty($dato['imagen_caja']) && is_array($dato['imagen_caja'])) {
                                                    $imagenes = array_merge($imagenes, $dato['imagen_caja']);
                                                }
                                            @endphp

                                            @if (!empty($imagenes))
                                                <div
                                                    style="display: grid; grid-template-columns: repeat(3, 80px); gap: 6px; padding: 0; margin: 0; justify-items: center; align-items: center; width: 100%;">

                                                    @foreach ($imagenes as $img)
                                                        <div style="width: 80px; height: 80px;">
                                                            <img src="{{ asset('storage/' . $img) }}" alt="Imagen"
                                                                width="80" height="80"
                                                                style="display: block; margin: 0; cursor:pointer"
                                                                onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">Sin Imagen</span>
                                            @endif
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
                            {{ $datosPaginados->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Lightbox -->
        <div id="lightbox" class="lightbox" onclick="this.style.display='none'">
            <img id="lightbox-img" src="">
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
</div>
