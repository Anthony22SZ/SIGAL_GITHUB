<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ContraseñaController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 2.0,
        ]);
    }

    /**
     * Solicita la recuperación de contraseña
     */
    public function solicitarRecuperacion(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
        ]);

        try {
            $response = $this->client->post('/solicitar-recuperacion', [
                'json' => [
                    'correo_electronico' => $request->correo_electronico,
                ]
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents(), true)
            );
        } catch (\Exception $e) {
            Log::error('Error en recuperación de contraseña: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar la solicitud'], 500);
        }
    }

    /**
     * Verifica el código de recuperación
     */
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);
    
        try {
            $response = $this->client->post('/verificar-codigo', [
                'json' => ['codigo' => $request->codigo]
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
    
            if (isset($data['token'])) {
                $request->session()->put('verification_token', $data['token']);
                return response()->json(['message' => 'Código verificado correctamente']);
            } else {
                return response()->json(['error' => 'Código de verificación incorrecto.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error en verificación de código: ' . $e->getMessage());
            return response()->json(['error' => 'Error al verificar el código'], 500);
        }
    }

    /**
     * Muestra el formulario de cambio de contraseña
     */
    public function mostrarFormulario()
    {
        if (!session()->has('verification_token')) {
            return redirect()->route('login')
                           ->with('error', 'Sesión expirada');
        }
        
        return view('CambiarContrasena');
    }

    /**
     * Procesa el cambio de contraseña
     */
    public function cambiarContrasena(Request $request)
    {
        $request->validate([
            'nueva_contrasena' => 'required|min:8',
        ]);

        // Obtener el token de la sesión
        $token = session('verification_token');

        if (!$token) {
            return response()->json([
                'message' => 'Token no encontrado'
            ], 401);
        }

        try {
            $response = $this->client->post('/cambiar-contrasena', [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json'
                ],
                'json' => $request->only([
                    'nueva_contrasena',
                ])
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            // Limpiar el token después de usarlo
            $request->session()->forget('verification_token');
            
            return response()->json([
                'message' => $data['message'] ?? 'Contraseña actualizada correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

