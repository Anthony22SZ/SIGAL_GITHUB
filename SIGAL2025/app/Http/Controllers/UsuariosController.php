<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }

    public function insertarUsuario(Request $request)
    {
        $data = $request->all();

        try {
            $response = $this->client->post('/usuarios', [
                'json' => $data
            ]);

            return redirect()->route('usuarios.listar')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
       
    }

    public function crearUsuario(Request $request)
    {
        $nombre = $request->query('nombre', '');
        $correo = $request->query('correo', '');
        $cod_empleado = $request->query('cod_empleado', '');

        try {
            // Hacer la solicitud GET al servidor Node.js para obtener los roles
            $response = $this->client->get('/roles');
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Verificar si la respuesta tiene la estructura esperada
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                $roles = $responseData['data'];
            } else {
                $roles = [];
            }
        } catch (\Exception $e) {
            // Capturar errores de la solicitud HTTP
            Log::error('Error fetching roles: ' . $e->getMessage());
            $roles = [];
        }

        return view('CrearUsuario', compact('nombre', 'correo', 'roles', 'cod_empleado'));
    }

    public function listarUsuarios()
    {
        try {
            $response = $this->client->get('/usuarios');
            $responseData = json_decode($response->getBody()->getContents(), true);
            
            // Debug the response
            Log::debug('API Response: ' . json_encode($responseData));
            
            // Check if the response has the expected structure
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                // The data property contains the array of users directly
                $usuarios = $responseData['data'];
                
                // Debug the users array
                Log::debug('Usuarios array: ' . json_encode($usuarios));
            } else {
                $usuarios = [];
                Log::warning('API response does not have the expected structure');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching usuarios: ' . $e->getMessage());
            $usuarios = [];
        }
    
        return view('Usuarios', compact('usuarios'));
    }
}

