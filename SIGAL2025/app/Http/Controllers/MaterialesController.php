<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MaterialesController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0, // Increased timeout for better reliability
        ]);
    }

    public function insertarMaterial(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'codigo' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s]+$/', // Alfanumérico',
            'material' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s]+$/', // Alfanumérico',
        ]);

        // Obtener todos los datos del request
        $data = $request->all();

        try {
            // Hacer la solicitud POST al servidor Node.js
            $response = $this->client->post('/materiales', [
                'json' => $data // Enviar los datos en formato JSON
            ]);

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Redirigir a la lista de materiales con un mensaje de éxito
            return redirect()->route('materiales.listar')->with('success', $responseData['message']);
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al insertar material: ' . $errorMessage);

            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors(['error' => 'Error al insertar el material: ' . $errorMessage]);
        }
    }

    public function crearMaterial()
    {
        return view('CrearMaterial');
    }

    public function listarMateriales(Request $request)
    {
        try {
            // Parámetros de búsqueda
            $codigo = $request->query('codigo', null);
            $material = $request->query('material', null);

            // Hacer la solicitud GET al servidor Node.js con parámetros de búsqueda
            $response = $this->client->get('/materiales', [
                'query' => [
                    'codigo' => $codigo,
                    'material' => $material
                ]
            ]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Debug the response
            Log::debug('API Response: ' . json_encode($responseData));

            // Check if the response has the expected structure
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                $materiales = $responseData['data'];

                // Debug the materiales array
                Log::debug('Materiales array: ' . json_encode($materiales));
            } else {
                $materiales = [];
                Log::warning('API response does not have the expected structure');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching materiales: ' . $e->getMessage());
            $materiales = [];
        }

        return view('Materiales', compact('materiales'));
    }


    public function eliminarMaterial($codigo)
    {
        try {
            $resultado = pool::select("CALL sp_EliminarMaterial(?)", [$codigo]);

            return response()->json([
                'mensaje' => $resultado[0]->mensaje ?? 'Material eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el material',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}