<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\DetallePedido;
use App\Models\Bitacora;
use App\Models\InfoPuro;
use App\Models\InfoEmpaque;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    /**
     * Obtener lista de pedidos de producción
     */
    public function index(Request $request)
    {
        try {
            // Recibir filtros desde la request
            $filtro_cliente = $request->input('filtro_cliente');
            $filtro_codigo_puro = $request->input('filtro_codigo_puro');
            $filtro_presentacion = $request->input('filtro_presentacion', []); // Array
            $filtro_marca = $request->input('filtro_marca');
            $filtro_vitola = $request->input('filtro_vitola');
            $filtro_alias_vitola = $request->input('filtro_alias_vitola');
            $filtro_capa = $request->input('filtro_capa');
            $filtro_codigo_empaque = $request->input('filtro_codigo_empaque');

            $presentacionesString = !empty($filtro_presentacion) ? implode(',', $filtro_presentacion) : null;

            // Ejecutar el SP que usa el frontend
            $results = DB::select("CALL GetDetallePedido(?, ?, ?, ?, ?, ?, ?, ?)", [
                $filtro_cliente,
                $filtro_codigo_puro,
                $presentacionesString,
                $filtro_marca,
                $filtro_vitola,
                $filtro_alias_vitola,
                $filtro_capa,
                $filtro_codigo_empaque
            ]);

            // Mapear resultados para procesar JSON de imágenes
            $mappedResults = collect($results)->map(function ($row) {
                return [
                    'id_pedido' => $row->id_pedido ?? '',
                    'cliente' => $row->name_cliente ?? '',
                    'codigo_puro' => $row->codigo_puro ?? '',
                    'presentacion_puro' => $row->presentacion_puro ?? '',
                    'marca' => $row->marca ?? '',
                    'vitola' => $row->vitola ?? '',
                    'alias_vitola' => $row->alias_vitola ?? '',
                    'capa' => $row->capa ?? '',
                    'descripcion_produccion' => $row->descripcion_produccion ?? '',
                    // Transformar rutas relativas a URLs completas para la API
                    'imagen_produccion' => $row->imagen_produccion
                        ? collect(json_decode($row->imagen_produccion))->map(function ($path) {
                            return url('api/image-proxy?path=' . $path);
                        })->toArray()
                        : [],
                    'estado_pedido' => $row->estado_pedido ?? '',
                    'codigo_empaque' => $row->codigo_empaque ?? '',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $mappedResults
            ]);
        } catch (\Exception $e) {
            Log::error('API Produccion Index Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar detalle de pedido y subir imágenes
     */
    public function update(Request $request)
    {
        // Validar
        $validator = Validator::make($request->all(), [
            'id_pedido' => 'required|exists:detalle_pedido,id_pedido',
            'descripcion_produccion' => 'nullable|string',
            // imagenes[] puede ser un array de archivos
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'image|max:10240', // Max 10MB por imagen
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $id_pedido = $request->input('id_pedido');
            $descripcion = $request->input('descripcion_produccion');
            $userId = 1; // ID por defecto si no hay auth API implementada, o usar Auth::id() si hay token

            // Obtener datos actuales para nombres de archivo
            $pedido = DetallePedido::find($id_pedido);
            $puro = InfoPuro::find($pedido->id_puro);

            // 1. Actualizar descripción
            if ($descripcion) {
                $pedido->descripcion_produccion = $descripcion;
                $pedido->save();

                // Bitácora
                Bitacora::create([
                    'descripcion' => 'API: Actualización de producción. Desc: ' . $descripcion,
                    'id_pedido' => $id_pedido,
                    'accion' => 'Actualización API',
                    'id_usuario' => $userId,
                ]);
            }

            // 2. Manejo de Imágenes
            // Recuperar historial actual para no perder imagenes previas si no se envian nuevas
            $historialExistente = DB::table('historial_imagenes')
                ->where('id_pedido', $id_pedido)
                ->orderBy('fecha_cambio', 'desc')
                ->first();

            // Comenzamos con las imágenes que ya existían (del último historial)
            $imagenesProduccionPaths = $historialExistente ? json_decode($historialExistente->imagen_produccion) : [];

            // Anillado y caja se mantienen igual, solo copiamos lo que había
            $imagenesAnilladoPaths = $historialExistente ? json_decode($historialExistente->imagen_anillado) : [];
            $imagenesCajaPaths = $historialExistente ? json_decode($historialExistente->imagen_caja) : [];

            // Procesar NUEVAS imagenes subidas
            if ($request->hasFile('imagenes')) {
                $fechaActual = now()->format('Ymd_His');
                $codigo_puro = $puro ? $puro->codigo_puro : 'UNK';

                // Calcular indice para nombre
                $ultimoIndexProduccion = 0;
                if (!empty($imagenesProduccionPaths)) {
                    foreach ($imagenesProduccionPaths as $imagenPath) {
                        if (preg_match('/PROD-(\d+)-/', $imagenPath, $matches)) {
                            $index = (int) $matches[1];
                            if ($index >= $ultimoIndexProduccion) {
                                $ultimoIndexProduccion = $index + 1;
                            }
                        }
                    }
                }

                Log::info('Procesando imagenes API', ['cantidad' => count($request->allFiles())]);

                $imagenes = $request->file('imagenes');
                if (!is_array($imagenes)) {
                    $imagenes = [$imagenes];
                }

                foreach ($imagenes as $imagen) {
                    $extension = $imagen->getClientOriginalExtension();
                    $nombreArchivo = "{$id_pedido}-{$codigo_puro}-PROD-{$ultimoIndexProduccion}-{$fechaActual}.{$extension}";

                    // Guardar en storage/app/public/imagenes/produccion
                    $path = $imagen->storeAs("imagenes/produccion", $nombreArchivo, 'public');

                    // Agregar al array de paths
                    $imagenesProduccionPaths[] = $path;
                    $ultimoIndexProduccion++;

                    Log::info('Imagen guardada API', ['path' => $path]);
                }

                // Insertar nueva entrada en historial_imagenes solo si hubo cambios de imagenes o descripción
                // Aca asumimos que siempre generamos historial nuevo al llamar update
                DB::table('historial_imagenes')->insert([
                    'id_pedido' => $id_pedido,
                    'imagen_produccion' => !empty($imagenesProduccionPaths) ? json_encode($imagenesProduccionPaths) : null,
                    'imagen_anillado' => !empty($imagenesAnilladoPaths) ? json_encode($imagenesAnilladoPaths) : null,
                    'imagen_caja' => !empty($imagenesCajaPaths) ? json_encode($imagenesCajaPaths) : null,
                    'fecha_cambio' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido actualizado correctamente',
                'data' => [
                    'id_pedido' => $id_pedido,
                    'imagenes_totales' => count($imagenesProduccionPaths)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Produccion Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
