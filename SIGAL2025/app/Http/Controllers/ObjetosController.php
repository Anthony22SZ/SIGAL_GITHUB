<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ObjetosController extends Controller
{
    protected $client;

    public function __construct()
    {
        
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 10.0,
        ]);
    }

    /**
     * Listar todos los objetos
     */
    public function listarObjetos(Request $request)
{

        $permissions = $request->attributes->get('permissions', []);
        $usuario = $request->attributes->get('usuario');
    try {
        $response = $this->client->get('/objetos');
        $data = json_decode($response->getBody()->getContents(), true);

        Log::debug('Data received from /objetos: ' . json_encode($data));

        if (isset($data['success']) && $data['success']) {
            $objetos = $data['data'] ?? [];
            return view('Objetos', compact('objetos'));
        } else {
            $errorMessage = $data['message'] ?? 'Respuesta inesperada';
            Log::warning('Failed to fetch objects: ' . $errorMessage);
            // En lugar de back(), renderizamos la vista con un mensaje de error
            return view('Objetos')->withErrors(['error' => 'Error al obtener los objetos: ' . $errorMessage]);
        }
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
        Log::error('Error fetching objects: ' . $e->getMessage() . ' Response: ' . $responseBody);
        return view('Objetos')->withErrors(['error' => 'Error al obtener los objetos: ' . $responseBody]);
    } catch (\Exception $e) {
        Log::error('Unexpected error fetching objects: ' . $e->getMessage());
        return view('Objetos')->withErrors(['error' => 'Error al obtener los objetos: ' . $e->getMessage()]);
    }

    return view('Objetos', compact('objetos', 'permissions', 'usuario'));
}

    /**
     * Mostrar el formulario para crear un objeto
     */
    public function crear(Request $request)
    {
        $permissions = $request->attributes->get('permissions', []);
        $usuario = $request->attributes->get('usuario');

        return view('CrearObjetos', compact('permissions', 'usuario'));
    }

    /**
     * Insertar un nuevo objeto
     */
    public function insertar(Request $request)
    {
        $request->validate([
            'NOMBRE_OBJETO' => 'required|string|max:255',
            'DESCRIPCION_OBJETO' => 'nullable|string|max:255',
            'TIPO_OBJETO' => 'required|string|max:255',
        ]);

        try {
            $usuario = $request->attributes->get('usuario');
            $nombreUsuario = $usuario ? $usuario->nombre_usuario : 'Sistema';

            $data = [
                'nombre_objeto' => $request->input('NOMBRE_OBJETO'),
                'descripcion_objeto' => $request->input('DESCRIPCION_OBJETO') ?? '',
                'tipo_objeto' => $request->input('TIPO_OBJETO'),
                'usuario_crea' => $nombreUsuario,
            ];
            Log::debug('Data sent to /OBJETOS: ' . json_encode($data));

            $response = $this->client->post('/OBJETOS', [
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (isset($responseData['success']) && $responseData['success']) {
                return redirect()->route('objetos.listar')->with('success', 'Objeto creado exitosamente');
            } else {
                return back()->withErrors(['error' => 'Error al crear el objeto: ' . ($responseData['message'] ?? 'Respuesta inesperada')]);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
            Log::error('Error creating object: ' . $e->getMessage() . ' Response: ' . $responseBody);
            return back()->withErrors(['error' => 'Error al crear el objeto: ' . $responseBody]);
        } catch (\Exception $e) {
            Log::error('Unexpected error creating object: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el objeto: ' . $e->getMessage()]);
        }
    }
}