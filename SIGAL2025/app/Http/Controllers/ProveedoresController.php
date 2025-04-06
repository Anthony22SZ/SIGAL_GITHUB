<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class ProveedoresController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0, // Increased timeout for better reliability
        ]);
    }

    public function insertarProveedor(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'NOMBRE_PROVEEDOR' => 'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'NOMBRE_CONTACTO' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'APELLIDO_CONTACTO' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'NUMERO_IDENTIDAD' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'RTN' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'CALLE' => 'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'CIUDAD' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'PAIS' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'CODIGO_POSTAL' => 'required|string|max:10|regex:/^[0-9\s]+$/',
            'TELEFONO' => 'required|string|max:15|regex:/^[0-9-]+$/',
            'CORREO' => 'required|email|max:100',
        ]);
        // Obtener todos los datos del request
        $data = $request->all();

        try {
            // Hacer la solicitud POST al servidor Node.js
            $response = $this->client->post('/proveedores', [
                'json' => $data // Enviar los datos en formato JSON
            ]);

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);


            // Redirigir a la lista de proveedores con un mensaje de éxito
            return redirect()->route('proveedores.listar')->with('success', 'Proveedor insertado correctamente');
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al insertar proveedor: ' . $errorMessage);

            // Retornar una respuesta JSON con el error
            return response()->json([
                'success' => false,
                'message' => 'Error al insertar el proveedor',
                'error' => $errorMessage
            ], 500);

            
        }
    }

    public function crearProveedor()
    {
        return view('CrearProveedor');
    }

    public function listarProveedores(Request $request)
    {
        try {
            // Parámetros de paginación
            $page = $request->query('page', 1); // Página actual (por defecto 1)
            $limit = 5; // Proveedores por página

            // Hacer la solicitud GET al servidor Node.js con parámetros de paginación
            $response = $this->client->get('/proveedores', [
                'query' => [
                    'page' => $page,
                    'limit' => $limit
                ]
            ]);
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Debug the response
            Log::debug('API Response: ' . json_encode($responseData));

            // Check if the response has the expected structure
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                // The data property contains the array of proveedores directly
                $proveedores = $responseData['data'];

                $pagination = [
                    'current_page' => $responseData['pagination']['page'] ?? $page,
                    'per_page' => $responseData['pagination']['limit'] ?? $limit,
                    'total' => $responseData['pagination']['total'] ?? count($proveedores),
                    'total_pages' => $responseData['pagination']['totalPages'] ?? ceil(count($proveedores) / $limit),
                ];

                // Debug the proveedores array
                Log::debug('Proveedores array: ' . json_encode($proveedores));
            } else {
                $proveedores = [];
                $pagination = [
                    'current_page' => $page,
                    'per_page' => $limit,
                    'total' => 0,
                    'total_pages' => 0,
                ];
                Log::warning('API response does not have the expected structure');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching proveedores: ' . $e->getMessage());
            $proveedores = [];
            $pagination = [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => 0,
                'total_pages' => 0,
            ];
        }

        return view('Proveedores', compact('proveedores', 'pagination'));
    }

    public function eliminarProveedor($COD_PROVEEDOR)
    {
        try {
            // Hacer la solicitud DELETE al servidor Node.js
            $response = $this->client->delete("/eliminar-proveedor/{$COD_PROVEEDOR}");

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Verificar si la eliminación fue exitosa
            if (isset($responseData['mensaje'])) {
                return redirect()->route('proveedores.listar')->with('success', $responseData['mensaje']);
            } else {
                return redirect()->route('proveedores.listar')->with('error', 'Error al eliminar el proveedor');
            }
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al eliminar proveedor: ' . $errorMessage);

            // Redirigir con un mensaje de error
            return redirect()->route('proveedores.listar')->with('error', 'Error al eliminar el proveedor');
        }
    } 
    
    // Mostrar el formulario para actualizar un proveedor
    public function mostrarActualizarProveedor($COD_PROVEEDOR)
{
    $response = Http::get('http://127.0.0.1:3000/proveedores', [
        'cod_proveedor' => $COD_PROVEEDOR,
    ]);

    if ($response->failed() || empty($response->json()['data'])) {
        return redirect()->route('proveedores.listar')->with('error', 'Proveedor no encontrado o error en la API');
    }

    // Extraer el primer elemento del array "data" para que la vista no tenga que hacer cambios
    $proveedor = $response->json()['data'][0];

    return view('ActualizarProveedor', compact('proveedor'));
}

public function actualizarProveedor(Request $request, $COD_PROVEEDOR)
{
    $request->validate([
        'NOMBRE_PROVEEDOR' => 'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
        'NOMBRE_CONTACTO' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'APELLIDO_CONTACTO' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'NUMERO_IDENTIDAD' => 'required|string|max:20|regex:/^[0-9-]+$/',
        'RTN' => 'required|string|max:20|regex:/^[0-9-]+$/',
        'CALLE' => 'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
        'CIUDAD' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'PAIS' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'CODIGO_POSTAL' => 'required|string|max:10|regex:/^[0-9\s]+$/',
        'TELEFONO' => 'required|string|max:15|regex:/^[0-9-]+$/',
        'CORREO' => 'required|email|max:100',
    ]);

    $data = $request->all();
    $data['COD_PROVEEDOR'] = $COD_PROVEEDOR;
    Log::debug('Datos enviados a la API: ' . json_encode($data));
    Log::debug('URL de la solicitud: ' . $this->client->getConfig('base_uri') . "/actualizar-proveedor/{$COD_PROVEEDOR}");

    try {
        $response = $this->client->put("/actualizar-proveedor/{$COD_PROVEEDOR}", [
            'json' => $data
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        Log::debug('Respuesta de la API: ' . json_encode($responseData));

        if ($response->getStatusCode() === 200 && isset($responseData['message'])) {
            return redirect()->route('proveedores.listar')->with('success', 'Proveedor actualizado exitosamente');
        } else {
            Log::warning('Respuesta inesperada de la API: ' . json_encode($responseData));
            return redirect()->back()->with('error', 'Error al actualizar el proveedor');
        }
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        $errorMessage = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al actualizar proveedor: ' . $errorMessage);
        return redirect()->back()->with('error', 'Error al actualizar el proveedor: ' . $errorMessage);
    }
  }
}
