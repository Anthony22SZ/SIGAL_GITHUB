<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }

    public function listarRoles(Request $request)
    {
        $permissions = $request->attributes->get('permissions', []);
        $usuario = $request->attributes->get('usuario');

        try {
            $response = $this->client->get('/roles');
            $responseData = json_decode($response->getBody()->getContents(), true);
            Log::debug('API Response: ' . json_encode($responseData));
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                $roles = $responseData['data'];
                Log::debug('Roles array: ' . json_encode($roles));
            } else {
                $roles = [];
                Log::warning('API response does not have the expected structure');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching Roles: ' . $e->getMessage());
            $roles = [];
        }

        return view('Roles', compact('roles', 'permissions', 'usuario'));
    }

    public function crearRol(Request $request)
    {
        $permissions = $request->attributes->get('permissions', []);
        $usuario = $request->attributes->get('usuario');

        return view('CrearRoles', compact('permissions', 'usuario'));
    }

    public function insertarRol(Request $request)
    {
        $request->validate([
            'NOMBRE_ROL' => 'required|string|max:255',
            'DESCRIPCION_ROL' => 'required|string|max:255',
        ]);
    
        try {
            $usuario = $request->attributes->get('usuario');
            $nombreUsuario = $usuario ? $usuario->nombre_usuario : 'Sistema';
    
            $data = [
                'nombre_rol' => $request->input('NOMBRE_ROL'), // Minúsculas
                'descripcion_rol' => $request->input('DESCRIPCION_ROL'), // Minúsculas
                'usuario_crea' => $nombreUsuario, // Minúsculas
            ];
            Log::debug('Data sent to /ROLES: ' . json_encode($data));
    
            $response = $this->client->post('/ROLES', [
                'json' => $data
            ]);
    
            $responseData = json_decode($response->getBody()->getContents(), true);
            if (isset($responseData['success']) && $responseData['success']) {
                return redirect()->route('roles.listar')->with('success', 'Rol creado exitosamente');
            } else {
                return back()->withErrors(['error' => 'Error al crear el rol: ' . ($responseData['message'] ?? 'Respuesta inesperada')]);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
            Log::error('Error creating role: ' . $e->getMessage() . ' Response: ' . $responseBody);
            return back()->withErrors(['error' => 'Error al crear el rol: ' . $responseBody]);
        } catch (\Exception $e) {
            Log::error('Unexpected error creating role: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el rol: ' . $e->getMessage()]);
        }
    }
}