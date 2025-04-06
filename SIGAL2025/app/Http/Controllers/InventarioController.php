<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class InventarioController extends Controller
{
    public function listarInventario(Request $request)
    {
        // Obtener parámetros de la query
        $material_codigo = $request->query('material_codigo');
        $nombre_material = $request->query('nombre_material');
        $stock_minimo = $request->query('stock_minimo') === 'true'; // Convertir a booleano

        try {
            // Hacer la solicitud HTTP al endpoint de Node.js
            $response = Http::get('http://127.0.0.1:3000/inventario', [
                'material_codigo' => $material_codigo,
                'nombre_material' => $nombre_material,
                'stock_minimo' => $stock_minimo
            ]);

            // Verificar si la solicitud fue exitosa
            if (!$response->successful()) {
                throw new Exception('Error en la respuesta del servidor Node.js: ' . $response->status());
            }

            // Obtener los datos de la respuesta
            $data = $response->json();

            // Depuración: Imprimir la estructura de los datos recibidos
            \Log::info('Respuesta del endpoint:', $data);

            // Extraer el array de inventario directamente de data (sin anidamiento adicional)
            $inventario = $data['data'] ?? []; // Ajustado para coincidir con la respuesta del endpoint

            // Pasar los datos a la vista
            return view('InventarioMaterial', [
                'inventario' => $inventario,
                'material_codigo' => $material_codigo,
                'nombre_material' => $nombre_material,
                'stock_minimo' => $stock_minimo
            ]);

        } catch (Exception $error) {
            // Log del error
            \Log::error('Error al consultar inventario: ' . $error->getMessage());

            // Pasar un mensaje de error a la vista
            return view('InventarioMaterial', [
                'inventario' => [],
                'error' => 'Error al listar inventario: ' . $error->getMessage(),
                'material_codigo' => $material_codigo,
                'nombre_material' => $nombre_material,
                'stock_minimo' => $stock_minimo
            ]);
        }
    }

    public function registrarSalida(Request $request)
    {
        // Obtener los datos del formulario
        $codigo = $request->input('codigo');
        $cantidad = $request->input('cantidad');

        // Validar los datos
        if (!$codigo || $cantidad === null) {
            return response()->json([
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ], 400);
        }

        try {
            // Hacer la solicitud POST al endpoint de Node.js
            $response = Http::post('http://127.0.0.1:3000/salida-material', [
                'codigo' => $codigo,
                'cantidad' => $cantidad
            ]);

            // Verificar si la solicitud fue exitosa
            if (!$response->successful()) {
                throw new Exception('Error en la respuesta del servidor Node.js: ' . $response->status());
            }

            // Obtener la respuesta del endpoint
            $data = $response->json();

            // Devolver la respuesta al frontend
            return response()->json($data);

        } catch (Exception $error) {
            \Log::error('Error al registrar salida: ' . $error->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la salida de material',
                'error' => $error->getMessage()
            ], 500);
        }
    }
}