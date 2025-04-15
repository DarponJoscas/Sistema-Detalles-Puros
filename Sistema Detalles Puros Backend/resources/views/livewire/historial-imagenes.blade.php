<div>
    <style>
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
    <div>
        <div class="d-inline-block m-3" style="z-index: -800; position: absolute;">
            <div class="table-responsive text-center" wire:ignore.self>
                <div wire:ignore class="row g-0">
                    <div wire:ignore class="col px-1" style="width: 160px; flex: none;">
                        <select id="codigoPuro" wire:model="filtro_codigo_puro" wire:change="filtrarPedidos">
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

                <table class="table mt-3 mx-auto text-center align-middle">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%">ID Historial</th>
                            <th style="width: 5%">ID Pedido</th>
                            <th style="width: 10%">Código Puro</th>
                            <th style="width: 10%">Presentación</th>
                            <th style="width: 10%">Marca</th>
                            <th style="width: 10%">Vitola</th>
                            <th style="width: 10%">Alias Vitola</th>
                            <th style="width: 10%">Capa</th>
                            <th style="width: 10%">Imagen Producción</th>
                            <th>Imagenes Empaque</th>
                            <th style="width: 10%">Fecha Cambio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historialImagenes as $dato)
                            <tr>
                                <td>{{ $dato['id_historial'] }}</td>
                                <td>{{ $dato['id_pedido'] }}</td>
                                <td>{{ $dato['codigo_puro'] }}</td>
                                <td>{{ $dato['presentacion_puro'] }}</td>
                                <td>{{ $dato['marca'] }}</td>
                                <td>{{ $dato['vitola'] }}</td>
                                <td>{{ $dato['alias_vitola'] }}</td>
                                <td>{{ $dato['capa'] }}</td>
                                <td>
                                    @if (!empty($dato['imagen_produccion']) && is_array($dato['imagen_produccion']))
                                        <div
                                            style="display: grid; grid-template-columns: repeat(3, 80px); gap: 6px; padding: 0; margin: 0; justify-items: center; align-items: center; width: 100%;">
                                            @foreach ($dato['imagen_produccion'] as $img)
                                                <div style="width: 80px; height: 80px; padding: 0; margin: 0;">
                                                    <img src="{{ asset('storage/' . $img) }}" alt="Imagen producción"
                                                        width="80" height="80" class="rounded"
                                                        style="cursor:pointer; display: block; margin: 0;"
                                                        onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>


                                <td>
                                    @php
                                        $imagenes = [];
                                        if (!empty($dato['imagen_anillado']) && is_array($dato['imagen_anillado'])) {
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
                                                        width="80" height="80" style="display: block; margin: 0; cursor:pointer"
                                                        onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Sin Imagen</span>
                                    @endif
                                </td>
                                <td>{{ $dato['fecha_cambio'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="lightbox" class="lightbox" onclick="this.style.display='none'">
        <img id="lightbox-img" src="">
    </div>

    <script>
        function showLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').style.display = 'flex';
        }
    </script>

</div>
