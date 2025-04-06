<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Corrección aquí
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class EmpleadosController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0, // Increased timeout for better reliability
        ]);
    }

    public function insertarEmpleado(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'primer_nombre' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'segundo_nombre' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'primer_apellido' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'segundo_apellido' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
                'numero_identidad' => 'required|string|max:20|regex:/^[0-9-]+$/',
                'rtn' =>  'required|string|max:20|regex:/^[0-9-]+$/',
            'puesto' => 'required|string|in:ADMINISTRADOR,VENDEDOR,GERENTE,JEFE PRODUCCION,JEFE DE VENTAS',
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
        $data = $request->all();
        try {
            // Hacer la solicitud POST al servidor Node.js
            $response = $this->client->post('/empleados', [
                'json' => $data // Enviar los datos en formato JSON
            ]);

            // Decodificar la respuesta del servidor Node.js
            $responseData = json_decode($response->getBody()->getContents(), true);


            // Redirigir a la lista de proveedores con un mensaje de éxito
            return redirect()->route('empleados.listar')->with('success', 'Empleado insertado correctamente');
        } catch (RequestException $e) {
            // Capturar errores de la solicitud HTTP
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al insertar empleado: ' . $errorMessage);

            // Retornar una respuesta JSON con el error
            return response()->json([
                'success' => false,
                'message' => 'Error al insertar el empleado',
                'error' => $errorMessage
            ], 500);     
        }
    }

    
    public function crearEmpleado()
    {
        return view('CrearEmpleado');
    }


    public function listarEmpleados()
    {
        Log::info('Iniciando listarEmpleados');

        $response = Http::get('http://127.0.0.1:3000/empleados');

        if ($response->failed() || !$response->json()) {
            Log::warning('Error al obtener lista de empleados: ' . $response->body());
            $empleados = [];
        } else {
            $empleados = $response->json()['data'] ?? $response->json();
            Log::info('Lista de empleados obtenida: ', $empleados);
        }

        return view('Empleados', compact('empleados'));
    }


     // Mostrar el formulario para actualizar un cliente
     public function mostrarActualizarEmpleado($COD_EMPLEADO)
{
    $response = Http::get('http://127.0.0.1:3000/empleados', [
        'cod_empleado' => $COD_EMPLEADO,
    ]);
    
    if ($response->failed() || !$response->json()) {
        return redirect()->route('empleados.listar')->with('error', 'Empleado no encontrado o error en la API');
    }

    $empleado = $response->json()['data']; // Extraer el empleado de 'data'
    if (!$empleado) {
        return redirect()->route('empleados.listar')->with('error', 'Empleado no encontrado');
    }

    return view('ActualizarEmpleado', compact('empleado'));
}
public function actualizarEmpleado(Request $request, $COD_EMPLEADO)
{
    $request->validate([
        'PRIMER_NOMBRE_E' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'SEGUNDO_NOMBRE_E' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'PRIMER_APELLIDO_E' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'SEGUNDO_APELLIDO_E' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'NUMERO_IDENTIDAD' => 'required|string|max:20|regex:/^[0-9-]+$/',
        'RTN' => 'required|string|max:20|regex:/^[0-9-]+$/',
        'PUESTO' => 'required|string|in:ADMINISTRADOR,VENDEDOR,GERENTE,JEFE PRODUCCION,JEFE DE VENTAS',
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
 $response = Http::put('http://127.0.0.1:3000/actualizar-empleado', $request->all());
     
     if ($response->failed()) {
         return redirect()->back()->with('error', 'Error al actualizar el empleado');
     }

     return redirect()->route('empleados.listar')->with('success', 'Empleado actualizado exitosamente');
 }
}

