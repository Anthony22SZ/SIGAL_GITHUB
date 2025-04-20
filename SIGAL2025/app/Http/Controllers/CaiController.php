<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CaiController extends Controller
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
     * Listar CAIs
     */
    public function listar()
    {
        try {
            // Obtener los CAIs
            $responseCais = $this->client->get('/api/cai', [
                'query' => ['estado' => 'ACTIVO'] // Filtra por estado activo
            ]);
            $caisBody = json_decode($responseCais->getBody()->getContents(), true);
            Log::info('Respuesta de /api/cai:', $caisBody);

            if (!isset($caisBody['success']) || !$caisBody['success']) {
                $errorMessage = $caisBody['error'] ?? 'Error desconocido al obtener CAIs';
                Log::error('Error en /api/cai: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar los CAIs: ' . $errorMessage);
            }
            $cais = $caisBody['data'] ?? [];

            // Obtener los tipos de documento para el formulario
            $responseTipos = $this->client->get('/api/tipos-documento', [
                'query' => ['estado' => 'ACTIVO']
            ]);
            $tiposBody = json_decode($responseTipos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/tipos-documento:', $tiposBody);

            if (!isset($tiposBody['success']) || !$tiposBody['success']) {
                $errorMessage = $tiposBody['error'] ?? 'Error desconocido al obtener tipos de documento';
                Log::error('Error en /api/tipos-documento: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar los tipos de documento: ' . $errorMessage);
            }
            $tiposDocumento = $tiposBody['data'] ?? [];

            // Obtener los puntos de emisión para el formulario
            $responsePuntos = $this->client->get('/api/puntos-emision', [
                'query' => ['estado' => 'ACTIVO']
            ]);
            $puntosBody = json_decode($responsePuntos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/puntos-emision:', $puntosBody);

            if (!isset($puntosBody['success']) || !$puntosBody['success']) {
                $errorMessage = $puntosBody['error'] ?? 'Error desconocido al obtener puntos de emisión';
                Log::error('Error en /api/puntos-emision: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar los puntos de emisión: ' . $errorMessage);
            }
            $puntosEmision = $puntosBody['data'] ?? [];

            return view('CAI', compact('cais', 'tiposDocumento', 'puntosEmision'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al listar CAIs: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar los datos: ' . $errorMessage);
        }
    }


    /**
 * Mostrar formulario para crear un CAI
 */
public function mostrarFormularioCrear()
{
    try {
        // Obtener los tipos de documento para el formulario
        $responseTipos = $this->client->get('/api/tipos-documento', [
            'query' => ['estado' => 'ACTIVO']
        ]);
        $tiposBody = json_decode($responseTipos->getBody()->getContents(), true);
        Log::info('Respuesta de /api/tipos-documento:', $tiposBody);

        if (!isset($tiposBody['success']) || !$tiposBody['success']) {
            $errorMessage = $tiposBody['error'] ?? 'Error desconocido al obtener tipos de documento';
            Log::error('Error en /api/tipos-documento: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los tipos de documento: ' . $errorMessage
            ], 500);
        }
        $tiposDocumento = $tiposBody['data'] ?? [];

        // Obtener los puntos de emisión para el formulario
        $responsePuntos = $this->client->get('/api/puntos-emision', [
            'query' => ['estado' => 'ACTIVO']
        ]);
        $puntosBody = json_decode($responsePuntos->getBody()->getContents(), true);
        Log::info('Respuesta de /api/puntos-emision:', $puntosBody);

        if (!isset($puntosBody['success']) || !$puntosBody['success']) {
            $errorMessage = $puntosBody['error'] ?? 'Error desconocido al obtener puntos de emisión';
            Log::error('Error en /api/puntos-emision: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los puntos de emisión: ' . $errorMessage
            ], 500);
        }
        $puntosEmision = $puntosBody['data'] ?? [];

        // Renderizar la vista parcial del formulario
        return view('cai.formulario-crear', compact('tiposDocumento', 'puntosEmision'));
    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al cargar datos para el formulario de CAI: ' . $errorMessage);
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar los datos: ' . $errorMessage
        ], 500);
    }
}

    /**
     * Crear un nuevo CAI
     */
    public function crear(Request $request)
    {
        $request->validate([
            'codigo_cai' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after:fecha_emision',
            'cod_tipo_documento' => 'required|string|max:50',
            'cod_punto_emision' => 'required|string|max:50',
            'establecimiento' => 'required|string|max:50',
            'punto_emision_codigo' => 'required|string|max:50',
            'tipo_documento_codigo' => 'required|string|max:50',
            'rango_inicial' => 'required|string|max:20',
            'rango_final' => 'required|string|max:20',
        ]);

        $data = $request->only([
            'codigo_cai', 'fecha_emision', 'fecha_vencimiento', 'cod_tipo_documento',
            'cod_punto_emision', 'establecimiento', 'punto_emision_codigo',
            'tipo_documento_codigo', 'rango_inicial', 'rango_final'
        ]);
        Log::info('Datos enviados para crear CAI:', $data);

        try {
            $response = $this->client->post('/api/cai', [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al crear CAI:', $responseBody);

            if ($response->getStatusCode() === 201) {
                return response()->json([
                    'success' => true,
                    'message' => $responseBody['message'] ?? 'CAI creado exitosamente',
                ], 201);
            }

            $errorMessage = $responseBody['error'] ?? 'Error desconocido al crear el CAI';
            Log::error('Error al crear CAI: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], $response->getStatusCode());
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al crear CAI: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el CAI: ' . $errorMessage,
            ], 500);
        }
    }
}

