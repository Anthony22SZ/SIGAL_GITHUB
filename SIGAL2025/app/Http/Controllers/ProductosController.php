<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Corrección aquí
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ProductosController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }

    public function insertarProducto(Request $request)
    {
        Log::info('Solicitud recibida en insertarProducto', $request->all());
    
        $request->validate([
            'CODIGO' => 'required|string|max:15|regex:/^[a-zA-Z0-9\-]+$/',
            'MODELO' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s]+$/',
            'DESCRIPCION' => 'required|string|max:200|regex:/^[a-zA-Z0-9\s]+$/',
            'COD_CATEGORIA' => 'required|integer|min:1',
            'TIEMPO_GARANTIA' => 'required|integer|min:0'
        ]);
    
        $data = $request->only(['CODIGO', 'MODELO', 'DESCRIPCION', 'COD_CATEGORIA', 'TIEMPO_GARANTIA']);
    
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://127.0.0.1:3000/productos', $data);
    
            Log::info('Respuesta de Node.js:', ['status' => $response->status(), 'body' => $response->body()]);
    
            if ($response->failed()) {
                Log::error('Fallo al crear producto:', ['status' => $response->status(), 'body' => $response->body()]);
                return redirect()->back()->with('error', 'Error al crear el producto: ' . $response->body())->withInput();
            }
    
            return redirect()->route('productos.listar')->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear producto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear el producto: ' . $e->getMessage())->withInput();
        }
    }

    public function mostrarCrearProducto()
    {
        try {
            // Obtener las categorías desde el endpoint de Node.js
            $response = Http::get('http://127.0.0.1:3000/categorias');

            Log::info('Respuesta de Node.js al obtener categorías:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed() || !$response->json('data')) {
                Log::error('Fallo al obtener las categorías:', ['status' => $response->status(), 'body' => $response->body()]);
                return redirect()->route('productos.listar')->with('error', 'Error al cargar las categorías');
            }

            $categorias = $response->json('data');
            return view('CrearProducto', compact('categorias'));
        } catch (\Exception $e) {
            Log::error('Error al cargar el formulario de creación: ' . $e->getMessage());
            return redirect()->route('productos.listar')->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    public function listarProductos(Request $request)
    {
        $codigo = $request->query('codigo');
        Log::info('Solicitud para listar productos:', ['codigo' => $codigo]);
        try {
            $queryParams = $codigo ? ['codigo' => $codigo] : [];
            $response = Http::get('http://127.0.0.1:3000/productos', $queryParams);
            Log::info('Respuesta de Node.js:', ['status' => $response->status(), 'body' => $response->body()]);
            if ($response->failed() || !$response->json('data')) {
                Log::error('Fallo al obtener la lista de productos:', ['status' => $response->status(), 'body' => $response->body()]);
                return redirect()->back()->with('error', 'Error al obtener la lista de productos');
            }
            $productos = $response->json('data');
            return view('Productos', compact('productos'));
        } catch (\Exception $e) {
            Log::error('Error al listar productos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al listar productos: ' . $e->getMessage());
        }
    }
    // Mostrar el formulario para actualizar un producto
    public function mostrarActualizarProducto($COD_PRODUCTO)
{
    $response = Http::get('http://127.0.0.1:3000/productos', [
        'codigo' => $COD_PRODUCTO,
    ]);
    
    if ($response->failed() || !$response->json() || empty($response->json('data'))) {
        return redirect()->route('productos.listar')->with('error', 'Producto no encontrado o error en la API');
    }

    $producto = $response->json('data')[0];
    return view('ActualizarProducto', compact('producto'));
}

    // Procesar la actualización del producto
    public function actualizarProducto(Request $request, $COD_PRODUCTO)
    {
        Log::info('Solicitud recibida en actualizarProducto', ['COD_PRODUCTO' => $COD_PRODUCTO, 'request' => $request->all()]);

        $request->validate([
            'CODIGO' => 'required|string|max:15|regex:/^[a-zA-Z0-9\-]+$/',
            'MODELO' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s]+$/',
            'DESCRIPCION' => 'required|string|max:200|regex:/^[a-zA-Z0-9\s]+$/',
            'COD_CATEGORIA' => 'required|integer',
            'TIEMPO_GARANTIA' => 'required|integer|min:0'
        ]);

        $data = $request->only(['CODIGO', 'MODELO', 'DESCRIPCION', 'COD_CATEGORIA', 'TIEMPO_GARANTIA']);
        Log::info('Datos enviados a Node.js para actualizar:', array_merge(['COD_PRODUCTO' => $COD_PRODUCTO], $data));

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->send('PUT', "http://127.0.0.1:3000/productos/{$COD_PRODUCTO}", [
                'body' => json_encode($data)
            ]);

            Log::info('Respuesta completa de Node.js:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                $errorBody = $response->body();
                Log::error('Fallo al actualizar producto:', ['status' => $response->status(), 'body' => $errorBody]);
                return redirect()->back()->with('error', 'Error al actualizar el producto: ' . $errorBody)->withInput();
            }

            Log::info('Actualización exitosa, redirigiendo a la lista');
            return redirect()->route('productos.listar')->with('success', 'Producto actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar producto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el producto: ' . $e->getMessage())->withInput();
        }
    }


    public function eliminarProducto($COD_PRODUCTO)
{
    try {
        $response = Http::delete("http://127.0.0.1:3000/productos/{$COD_PRODUCTO}");

        Log::info('Respuesta de Node.js al eliminar productos:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->failed()) {
            $errorBody = $response->body();
            Log::error('Fallo al eliminar productos:', ['status' => $response->status(), 'body' => $errorBody]);
            return redirect()->route('productos.listar')->with('error', 'Error al eliminar la productos: ' . $errorBody);
        }

        return redirect()->route('productos.listar')->with('success', 'Producto eliminado exitosamente');
    } catch (\Exception $e) {
        Log::error('Error al eliminar productos: ' . $e->getMessage());
        return redirect()->route('productos.listar')->with('error', 'Error al eliminar la productos: ' . $e->getMessage());
    }
}
}