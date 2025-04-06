<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TiposDocumentosController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 10.0,
            'connect_timeout' => 5.0,
        ]);
    }

    /**
     * Listar tipos de documento
     */
    public function listar()
    {
        try {
            // Obtener los tipos de documento
            $responseTipos = $this->client->get('/api/tipos-documento', [
                'query' => ['estado' => 'ACTIVO'] // Filtra por estado activo
            ]);
            $tiposBody = json_decode($responseTipos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/tipos-documento:', $tiposBody);

            if (!isset($tiposBody['success']) || !$tiposBody['success']) {
                $errorMessage = $tiposBody['error'] ?? 'Error desconocido al obtener tipos de documento';
                Log::error('Error en /api/tipos-documento: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar los tipos de documento: ' . $errorMessage);
            }
            $tiposDocumento = $tiposBody['data'] ?? [];

            return view('TiposDocumentos', compact('tiposDocumento'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al listar tipos de documento: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar los datos: ' . $errorMessage);
        }
    }

    /**
     * Mostrar formulario para crear un tipo de documento
     */
    public function mostrarFormularioCrear()
    {
        return view('tipos-documento.crear');
    }

    /**
     * Crear un nuevo tipo de documento
     */
    public function crear(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'afecta_inventario' => 'nullable|boolean',
            'requiere_cliente' => 'nullable|boolean',
        ]);

        $data = [
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'afecta_inventario' => $request->has('afecta_inventario') ? (bool)$request->afecta_inventario : false,
            'requiere_cliente' => $request->has('requiere_cliente') ? (bool)$request->requiere_cliente : true,
        ];
        Log::info('Datos enviados para crear tipo de documento:', $data);

        try {
            $response = $this->client->post('/api/tipos-documento', [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al crear tipo de documento:', $responseBody);

            if ($response->getStatusCode() === 201) {
                return response()->json([
                    'success' => true,
                    'message' => $responseBody['message'] ?? 'Tipo de documento creado exitosamente',
                ], 201);
            }

            $errorMessage = $responseBody['error'] ?? 'Error desconocido al crear el tipo de documento';
            Log::error('Error al crear tipo de documento: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], $response->getStatusCode());
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al crear tipo de documento: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de documento: ' . $errorMessage,
            ], 500);
        }
    }
}