<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Corrección aquí
use GuzzleHttp\Client;

class ClientesController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }
    // Mostrar la lista de clientes
    public function listarCliente()
    {
        $response = Http::get('http://127.0.0.1:3000/clientes');
        
        if ($response->failed() || !$response->json()) {
            $clientes = [];
        } else {
            $clientes = $response->json();
        }

        return view('Clientes', compact('clientes'));
    }

    public function insertarCliente(Request $request)
    {
            // Validar los datos del formulario
            $request->validate([
                'primer_nombre' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'segundo_nombre' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'primer_apellido' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'segundo_apellido' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'numero_identidad' => 'required|string|max:20|regex:/^[0-9-]+$/',
                'rtn' =>  'required|string|max:20|regex:/^[0-9-]+$/',
                'numero_telefono' => 'required|string|max:15|regex:/^[0-9-]+$/',
                'tipo_telefono' => 'required|string|in:PERSONAL,LABORAL,OTRO',
                'correo_electronico' => 'required|email|max:100',
                'tipo_correo' =>  'required|string|in:PERSONAL,LABORAL,OTRO',
                'calle' =>'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
                'ciudad' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'pais' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'codigo_postal' => 'required|string|max:10|regex:/^[0-9\s]+$/',
                'tipo_direccion' => 'required|string|in:DOMICILIO,LABORAL,OTRO',
                ]);

            // Obtener todos los datos del request
        $data = $request->all();

        try {
            // Hacer la solicitud POST al servidor Node.js
            $response = $this->client->post('/clientes', [
                'json' => $data // Enviar los datos en formato JSON
            ]);

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);


            // Redirigir a la lista de proveedores con un mensaje de éxito
            return redirect()->route('clientes.listar')->with('success', 'Cliente insertado correctamente');
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al insertar cliente: ' . $errorMessage);

            // Retornar una respuesta JSON con el error
            return response()->json([
                'success' => false,
                'message' => 'Error al insertar el cliente',
                'error' => $errorMessage
            ], 500);     
        }
    }
    
    public function crearCliente()
    {
        return view('CrearCliente');
    }

    public function eliminarCliente($COD_CLIENTE)
    {
        try {
            // Hacer la solicitud DELETE al servidor Node.js
            $response = $this->client->delete("/eliminar-cliente/{$COD_CLIENTE}");

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Verificar si la eliminación fue exitosa
            if (isset($responseData['mensaje'])) {
                return redirect()->route('clientes.listar')->with('success', $responseData['mensaje']);
            } else {
                return redirect()->route('clientes.listar')->with('error', 'Error al eliminar el cliente');
            }
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al eliminar cliente: ' . $errorMessage);

            // Redirigir con un mensaje de error
            return redirect()->route('clientes.listar')->with('error', 'Error al eliminar el cliente');
        }
    }

        // Mostrar el formulario para actualizar un cliente
        public function mostrarActualizarCliente($COD_CLIENTE)
        {
            $response = Http::get('http://127.0.0.1:3000/clientes', [
                'cod_cliente' => $COD_CLIENTE,
            ]);
            
            if ($response->failed() || !$response->json()) {
                return redirect()->route('clientes.listar')->with('error', 'Cliente no encontrado o error en la API');
            }

            $cliente = $response->json();
            return view('ActualizarCliente', compact('cliente'));
        }

    public function actualizarCliente(Request $request, $COD_CLIENTE)
    {
        // Validar los datos del formulario
        $request->validate([
            'PRIMER_NOMBRE_C' =>'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'SEGUNDO_NOMBRE_C' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'PRIMER_APELLIDO_C' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'SEGUNDO_APELLIDO_C' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'NUMERO_IDENTIDAD' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'RTN' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'NUMERO_TELEFONO' => 'required|string|max:15|regex:/^[0-9-]+$/',
            'TIPO_TELEFONO' => 'required|string|in:PERSONAL,LABORAL,OTRO',
            'CORREO_ELECTRONICO' => 'required|email|max:100',
            'TIPO_CORREO' => 'required|string|in:PERSONAL,LABORAL,OTRO',
            'CALLE' => 'required|string|max:100|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'CIUDAD' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'PAIS' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'CODIGO_POSTAL' => 'required|string|max:10|regex:/^[0-9\s]+$/',
            'TIPO_DIRECCION' => 'required|string|in:DOMICILIO,LABORAL,OTRO',
        ]);
    
    // Obtener todos los datos del request
    $data = $request->all();
    $response = Http::put('http://127.0.0.1:3000/actualizar-cliente', $request->all());
        
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al actualizar el cliente');
        }

        return redirect()->route('clientes.listar')->with('success', 'Cliente actualizado exitosamente');
    }
}