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
    <div class="table-responsive text-center" wire:ignore.self>
        <table class="table mt-3 mx-auto text-center align-middle">
            <thead class="text-center">
                <tr>
                    <th>ID Historial</th>
                    <th>ID Pedido</th>
                    <th>Imagen Producción</th>
                    <th>Imagen Anillado</th>
                    <th>Imagen Caja</th>
                    <th>Fecha Cambio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historialImagenes as $dato)
                    <tr>
                        <td>{{ $dato['id_historial'] }}</td>
                        <td>{{ $dato['id_pedido'] }}</td>
                        <td>
                            @if (!empty($dato['imagen_produccion']) && is_array($dato['imagen_produccion']))
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    @foreach ($dato['imagen_produccion'] as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Imagen producción" width="80"
                                            height="80" class="rounded" style="cursor:pointer"
                                            onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">Sin Imagen</span>
                            @endif
                        </td>

                        <td>
                            @if (!empty($dato['imagen_anillado']) && is_array($dato['imagen_anillado']))
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    @foreach ($dato['imagen_anillado'] as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Imagen anillado" width="80"
                                            height="80" class="rounded" style="cursor:pointer"
                                            onclick="showLightbox('{{ asset('storage/' . $img) }}')">
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">Sin Imagen</span>
                            @endif
                        </td>
                        <td>
                            @if (!empty($dato['imagen_caja']) && is_array($dato['imagen_caja']))
                                <div class="d-flex justify-content-center flex-wrap gap-2">
                                    @foreach ($dato['imagen_caja'] as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Imagen caja" width="80"
                                            height="80" class="rounded" style="cursor:pointer"
                                            onclick="showLightbox('{{ asset('storage/' . $img) }}')">
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

    <!-- Lightbox -->
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
