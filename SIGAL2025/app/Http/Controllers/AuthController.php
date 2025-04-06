<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 10.0,
        ]);
    }

    public function login(Request $request)
    {
        Log::info('Intento de login recibido', $request->all());
        $request->validate([
            'nombre_usuario' => 'required|string|max:8|regex:/^[a-zA-Z0-9]+$/',
            'contrasena' => 'required|string',
        ], [
            'nombre_usuario.required' => 'El nombre de usuario es obligatorio.',
            'nombre_usuario.max' => 'El nombre de usuario no puede exceder los 8 caracteres.',
            'nombre_usuario.regex' => 'El nombre de usuario solo puede contener letras y números.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        $nombre_usuario = $request->input('nombre_usuario');
        $contrasena = $request->input('contrasena');

        try {
            $response = $this->client->post('/login', [
                'json' => [
                    'nombre_usuario' => $nombre_usuario,
                    'contrasena' => $contrasena,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['token'])) {
                $token = $data['token'];
                $secret = env('JWT_SECRET', 'secreto_super_seguro');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));

                if ($decoded && isset($decoded->cod_usuario)) {
                    // Guardar el token y cod_usuario en la sesión de Laravel
                    $request->session()->put('token', $token);
                    $request->session()->put('cod_usuario', $decoded->cod_usuario);

                    Log::info('Usuario logueado: ' . $nombre_usuario, ['cod_usuario' => $decoded->cod_usuario]);
                    return response()->json(['message' => 'Login exitoso', 'redirect' => route('dashboard')]);
                } else {
                    Log::error('No se pudo decodificar el token o falta cod_usuario');
                    return response()->json(['error' => 'Error al procesar el token'], 400);
                }
            } else {
                Log::error('Credenciales incorrectas para usuario: ' . $nombre_usuario);
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Error al iniciar sesión: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error al iniciar sesión: ' . $e->getMessage()], 500);
        }
    }
    
    public function logout(Request $request)
    {
        // Aquí puedes agregar lógica para invalidar el token si es necesario
        $request->session()->forget('token'); // Eliminar el token de la sesión
    
        // Redirigir al login con un mensaje de confirmación
        return redirect('/')->with('message', 'Sesión cerrada correctamente');
    }


    public function solicitarRecuperacion(Request $request)
    {
        $correo_electronico = $request->input('correo_electronico');

        try {
            $response = $this->client->post('/solicitar-recuperacion', [
                'json' => [
                    'correo_electronico' => $correo_electronico,
                ]
            ]);

            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verificarCodigo(Request $request)
    {
        $codigo = $request->input('codigo');
    
        try {
            $response = $this->client->post('/verificar-codigo', [
                'json' => [
                    'codigo' => $codigo,
                ]
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
    
            if (isset($data['token'])) {
                $request->session()->put('verification_token', $data['token']);
                return response()->json(['message' => 'Código verificado correctamente.']);
            } else {
                return response()->json(['error' => 'Código de verificación incorrecto.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cambiarContrasena(Request $request)
{
    \Log::info("Solicitud para cambiar contraseña recibida");

    $nueva_contrasena = $request->input('nueva_contrasena');
    $confirmar_contrasena = $request->input('confirmar_contrasena');
    $token = $request->session()->get('verification_token'); 

    \Log::info("Nueva contraseña: " . $nueva_contrasena);
    \Log::info("Confirmar contraseña: " . $confirmar_contrasena);
    \Log::info("Token: " . $token);

    if (!$token) {
        \Log::error("Token no encontrado");
        return response()->json(['message' => 'Token no encontrado'], 401);
    }

    if ($nueva_contrasena !== $confirmar_contrasena) {
        \Log::error("Las contraseñas no coinciden");
        return response()->json(['message' => 'Las contraseñas no coinciden'], 400);
    }

    try {
        $response = $this->client->post('/cambiar-contrasena', [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Accept' => 'application/json'
            ],
            'json' => [
                'nueva_contrasena' => $nueva_contrasena,
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['success']) && $data['success']) {
            \Log::info("Contraseña cambiada correctamente");
            return redirect('/')->with('message', 'Contraseña cambiada correctamente. Por favor, inicia sesión.');
        } else {
            \Log::error("No se pudo cambiar la contraseña");
            return response()->json(['error' => 'No se pudo cambiar la contraseña'], 500);
        }
    } catch (\Exception $e) {
        \Log::error("Error al cambiar la contraseña: " . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}

