<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PuntosEmisionController extends Controller
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
     * Listar puntos de emisión y sucursales
     */
    public function listar()
    {
        try {
            // Obtener los puntos de emisión
            $responsePuntos = $this->client->get('/api/puntos-emision', [
                'query' => ['estado' => 'ACTIVO'] // Filtra por estado activo
            ]);
            $puntosBody = json_decode($responsePuntos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/puntos-emision:', $puntosBody);

            if (!isset($puntosBody['success']) || !$puntosBody['success']) {
                $errorMessage = $puntosBody['error'] ?? 'Error desconocido al obtener puntos de emisión';
                Log::error('Error en /api/puntos-emision: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar los puntos de emisión: ' . $errorMessage);
            }
            $puntosEmision = $puntosBody['data'] ?? [];

            // Obtener las sucursales
            $responseSucursales = $this->client->get('/sucursales'); // Ajustado según tu API
            $sucursalesBody = json_decode($responseSucursales->getBody()->getContents(), true);
            Log::info('Respuesta de /sucursales:', $sucursalesBody);

            if (!isset($sucursalesBody['success']) || !$sucursalesBody['success']) {
                $errorMessage = $sucursalesBody['message'] ?? 'Error desconocido al obtener sucursales';
                Log::error('Error en /sucursales: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar las sucursales: ' . $errorMessage);
            }
            $sucursales = $sucursalesBody['data'] ?? [];

            return view('listar', compact('puntosEmision', 'sucursales'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al listar puntos de emisión o sucursales: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar los datos: ' . $errorMessage);
        }
    }

    /**
     * Mostrar formulario para crear un punto de emisión
     */
    public function mostrarFormularioCrear()
    {
        try {
            // Obtener las sucursales para el formulario
            $responseSucursales = $this->client->get('/sucursales');
            $sucursalesBody = json_decode($responseSucursales->getBody()->getContents(), true);
            Log::info('Respuesta de /sucursales:', $sucursalesBody);

            if (!isset($sucursalesBody['success']) || !$sucursalesBody['success']) {
                $errorMessage = $sucursalesBody['message'] ?? 'Error desconocido al obtener sucursales';
                Log::error('Error en /sucursales: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar las sucursales: ' . $errorMessage);
            }
            $sucursales = $sucursalesBody['data'] ?? [];

            return view('puntos-emision.crear', compact('sucursales'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al cargar sucursales: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar las sucursales: ' . $errorMessage);
        }
    }

    /**
     * Crear un nuevo punto de emisión
     */
    public function crear(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|digits:3', // Solo 3 dígitos numéricos
            'nombre' => 'required|string|max:30', // Cadena de texto, máximo 100 caracteres
            'establecimiento' => 'required|digits:3', // Solo 3 dígitos numéricos
            'cod_sucursal' => 'required|string|max:20|exists:sucursales,COD_SUCURSAL', // Cadena de texto, máximo 20 caracteres, debe existir en la tabla sucursales
        ]);

        $data = $request->only(['codigo', 'nombre', 'establecimiento', 'cod_sucursal']);
        Log::info('Datos enviados para crear punto de emisión:', $data);

        try {
            $response = $this->client->post('/api/puntos-emision', [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al crear punto de emisión:', $responseBody);

            if ($response->getStatusCode() === 201) {
                return response()->json([
                    'success' => true,
                    'message' => $responseBody['message'] ?? 'Punto de emisión creado exitosamente',
                ], 201);
            }

            // Si el código de estado no es 201, devolver el mensaje de error de la API
            $errorMessage = $responseBody['error'] ?? 'Error desconocido al crear el punto de emisión';
            Log::error('Error al crear punto de emisión: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], $response->getStatusCode());
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al crear punto de emisión: ' . $errorMessage);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el punto de emisión: ' . $errorMessage,
            ], 500);
        }
    }

    /**
     * Mostrar formulario para editar un punto de emisión
     */
    public function mostrarFormularioEditar($cod_punto_emision)
    {
        try {
            $response = $this->client->get('/api/puntos-emision');
            $puntosBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de /api/puntos-emision:', $puntosBody);

            if (!isset($puntosBody['success']) || !$puntosBody['success']) {
                $errorMessage = $puntosBody['error'] ?? 'Error desconocido al obtener puntos de emisión';
                Log::error('Error en /api/puntos-emision: ' . $errorMessage);
                return redirect()->route('puntos-emision.listar')->with('error', 'Error al cargar los puntos de emisión: ' . $errorMessage);
            }
            $puntosEmision = $puntosBody['data'] ?? [];
            $puntoEmision = collect($puntosEmision)->firstWhere('COD_PUNTO_EMISION', $cod_punto_emision);

            if (!$puntoEmision) {
                return redirect()->route('puntos-emision.listar')->with('error', 'Punto de emisión no encontrado');
            }

            // Obtener las sucursales para el formulario de edición
            $responseSucursales = $this->client->get('/sucursales');
            $sucursalesBody = json_decode($responseSucursales->getBody()->getContents(), true);
            Log::info('Respuesta de /sucursales:', $sucursalesBody);

            if (!isset($sucursalesBody['success']) || !$sucursalesBody['success']) {
                $errorMessage = $sucursalesBody['message'] ?? 'Error desconocido al obtener sucursales';
                Log::error('Error en /sucursales: ' . $errorMessage);
                return redirect()->route('puntos-emision.listar')->with('error', 'Error al cargar las sucursales: ' . $errorMessage);
            }
            $sucursales = $sucursalesBody['data'] ?? [];

            return view('puntos-emision.editar', compact('puntoEmision', 'sucursales'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al cargar punto de emisión para editar: ' . $errorMessage);
            return redirect()->route('puntos-emision.listar')->with('error', 'Error al cargar el punto de emisión: ' . $errorMessage);
        }
    }

    /**
     * Actualizar un punto de emisión
     */
    public function actualizar(Request $request, $cod_punto_emision)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:100',
            'establecimiento' => 'required|string|max:50',
            'estado' => 'nullable|string|in:ACTIVO,INACTIVO',
            'cod_sucursal' => 'required|string|max:20',
        ]);

        $data = $request->only(['codigo', 'nombre', 'establecimiento', 'estado', 'cod_sucursal']);
        Log::info('Datos enviados para actualizar punto de emisión:', $data);

        try {
            $response = $this->client->put("/api/puntos-emision/{$cod_punto_emision}", [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al actualizar punto de emisión:', $responseBody);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('puntos-emision.listar')->with('success', 'Punto de emisión actualizado exitosamente');
            }

            return redirect()->back()->with('error', 'Error al actualizar el punto de emisión');
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al actualizar punto de emisión: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al actualizar el punto de emisión: ' . $errorMessage);
        }
    }
}

