<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Corrección aquí
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
class CategoriasController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }

 // LISTAR CATEGORIAS
    public function listarCategoria()
    {
    $query = request()->only(['cod_categoria', 'nombre']);
    $response = Http::get('http://127.0.0.1:3000/categorias', $query);
    
        if ($response->failed() || !$response->json()) {
            $categorias = [];
        } else {
            $categorias = $response->json('data', []);
        }

    return view('Categorias', compact('categorias'));
    }

    // INSERTAR CATEGORIAS
    public function insertarCategoria(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'descripcion' => 'required|string|max:100|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9\s]+$/',
    ]);

    $data = $request->only(['nombre', 'descripcion']);
    Log::info('Datos enviados a Node.js desde Laravel:', $data);

    try {
        // Enviar datos directamente como JSON sin la clave 'json'
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->send('POST', 'http://127.0.0.1:3000/categorias', [
            'body' => json_encode($data)
        ]);

        Log::info('Respuesta completa de Node.js:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            $errorBody = $response->body();
            Log::error('Respuesta fallida del servidor Node.js:', ['status' => $response->status(), 'body' => $errorBody]);
            throw new \Exception("Error en la respuesta del servidor: " . $errorBody);
        }

        $responseData = $response->json();
        Log::info('Datos decodificados de Node.js:', $responseData);

        return redirect()->route('categorias.listar')->with('success', 'Categoría insertada correctamente');

    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al insertar categoría (RequestException): ' . $errorMessage);
        return response()->json([
            'success' => false,
            'message' => 'Error al insertar la categoría',
            'error' => $errorMessage
        ], 500);
    } catch (\Exception $e) {
        Log::error('Error al insertar categoría (Exception): ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al insertar la categoría',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function crearCategoria()
    {
        return view('CrearCategoria');
    }
     
   
    // Mostrar el formulario para actualizar una categoría
    public function mostrarActualizarCategoria($COD_CATEGORIA)
    {
        $response = Http::get('http://127.0.0.1:3000/categorias', [
            'cod_categoria' => $COD_CATEGORIA,
        ]);
        
        if ($response->failed() || !$response->json() || empty($response->json('data'))) {
            return redirect()->route('categorias.listar')->with('error', 'Categoría no encontrada o error en la API');
        }

        $categoria = $response->json('data')[0]; // Tomamos el primer resultado
        return view('ActualizarCategoria', compact('categoria'));
    }

    public function actualizarCategoria(Request $request, $COD_CATEGORIA)
{
    Log::info('Solicitud recibida en actualizarCategoria', ['COD_CATEGORIA' => $COD_CATEGORIA, 'request' => $request->all()]);

    $request->validate([
        'NOMBRE' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
        'DESCRIPCION' => 'required|string|max:100|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9\s]+$/',
    ]);

    $data = $request->only(['NOMBRE', 'DESCRIPCION']);
    Log::info('Datos enviados a Node.js para actualizar:', array_merge(['COD_CATEGORIA' => $COD_CATEGORIA], $data));

    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->send('PUT', "http://127.0.0.1:3000/categorias/{$COD_CATEGORIA}", [
            'body' => json_encode($data)
        ]);

        Log::info('Respuesta completa de Node.js:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            $errorBody = $response->body();
            Log::error('Fallo al actualizar categoría:', ['status' => $response->status(), 'body' => $errorBody]);
            return redirect()->back()->with('error', 'Error al actualizar la categoría: ' . $errorBody)->withInput();
        }

        Log::info('Actualización exitosa, redirigiendo a la lista');
        return redirect()->route('categorias.listar')->with('success', 'Categoría actualizada exitosamente');

    } catch (\Exception $e) {
        Log::error('Error al actualizar categoría: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al actualizar la categoría: ' . $e->getMessage())->withInput();
    }
}

public function eliminarCategoria($COD_CATEGORIA)
{
    try {
        $response = Http::delete("http://127.0.0.1:3000/categorias/{$COD_CATEGORIA}");

        Log::info('Respuesta de Node.js al eliminar categoría:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            $errorBody = $response->body();
            Log::error('Fallo al eliminar categoría:', ['status' => $response->status(), 'body' => $errorBody]);
            return redirect()->route('categorias.listar')->with('error', 'Error al eliminar la categoría: ' . $errorBody);
        }

        return redirect()->route('categorias.listar')->with('success', 'Categoría eliminada exitosamente');
    } catch (\Exception $e) {
        Log::error('Error al eliminar categoría: ' . $e->getMessage());
        return redirect()->route('categorias.listar')->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
    }
}
}

