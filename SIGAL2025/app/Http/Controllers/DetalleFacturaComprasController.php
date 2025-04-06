<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DetalleFacturaComprasController extends Controller
{

    public function __construct()
    {
        // Configurar el cliente HTTP para conectarse al backend de Node.js
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 10.0, // Aumenta el timeout si es necesario
        ]);
    }

    /**
     * Muestra la vista para crear los detalles de una factura de compra.
     *
     * @param int $cod_factura El ID de la factura a la que se asociarán los detalles.
     * @return \Illuminate\View\View Vista para crear los detalles de la factura.
     */
    public function crearDetalle($cod_factura)
    {
        try {
            // Obtener la lista de materiales desde el backend de Node.js
            $responseMateriales = $this->client->get('/materiales');
            $materialesData = json_decode($responseMateriales->getBody()->getContents(), true);

            // Verificar si la respuesta tiene la estructura esperada
            if (!isset($materialesData['success']) || !$materialesData['success'] || !isset($materialesData['data'])) {
                throw new \Exception('Respuesta inválida al obtener materiales');
            }
            $materiales = $materialesData['data'];

        } catch (\Exception $e) {
            // Manejar errores de conexión o del backend
            Log::error('Error fetching materiales: ' . $e->getMessage());
            $materiales = [];
        }

        // Pasar los datos a la vista
        return view('CrearDetalleFacturaCompra', compact('cod_factura', 'materiales'));
    }

    /**
     * Guarda los detalles de una factura de compra.
     *
     * @param Request $request La solicitud HTTP con los detalles de la factura.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el resultado de la operación.
     */
    public function guardarDetalles(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'cod_factura' => 'required|integer',
            'detalles' => 'required|array|min:1',
            'detalles.*.codigo' => 'required|integer',
            'detalles.*.cantidad' => 'required|numeric|min:1',
            'detalles.*.precio' => 'required|numeric|min:0.01',
        ]);

        try {
            // Enviar los datos de la factura al backend de Node.js
            $response = $this->client->post('/detalle-compra', [
                'json' => $request->all(),
            ]);

            // Decodificar la respuesta del backend de Node.js
            $data = json_decode($response->getBody()->getContents(), true);

            // Verificar si el backend de Node.js devolvió un error
            if (!$data['success']) {
                throw new \Exception($data['message'] ?? 'Error en el backend de Node.js');
            }

            // Devolver la respuesta al frontend
            return response()->json([
                'success' => true,
                'message' => $data['message'], // Mensaje del backend de Node.js
            ]);
        } catch (\Exception $e) {
            // Manejar errores de conexión o del backend
            Log::error('Error al guardar los detalles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar los detalles: ' . $e->getMessage(),
            ], 500);
        }
    }
}