<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB
use Illuminate\Support\Facades\Http; // Corrección aquí
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class InventariosProductosController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }



    private function getSucursales()
    {
        try {
            $response = Http::timeout(10)
                ->get('http://127.0.0.1:3000/sucursales');

            Log::info('Respuesta de Node.js al obtener sucursales:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed() || !$response->json('success')) {
                Log::error('Fallo al obtener las sucursales:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [];
            }

            return $response->json('data', []);
        } catch (\Exception $e) {
            Log::error('Error al obtener sucursales: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }
    
    // Listar inventario de productos
    public function listarInventarioProducto(Request $request)
    {
        try {
            // Log para depuración
            Log::info('Solicitud recibida en listarInventarioProducto:', [
                'method' => $request->method(),
                'query' => $request->query(),
                'headers' => $request->headers->all()
            ]);

            $nombreSucursal = $request->query('nombre_sucursal', null);

            // Make HTTP request to Node.js API
            $response = Http::timeout(10)
                ->get('http://127.0.0.1:3000/inventario-productos', [
                    'nombre_sucursal' => $nombreSucursal
                ]);

            // Log the response details
            Log::info('Respuesta de Node.js al listar inventario:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Check if the request failed or if success is false in the response
            if ($response->failed() || !$response->json('success')) {
                Log::error('Fallo al obtener el inventario:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Error al listar el inventario'
                    ], 500);
                }

                return redirect()->back()->with('error', 'Error al listar el inventario');
            }

            // Get the inventory data
            $inventario = $response->json('data', []);

            // Si es una solicitud AJAX, devolver JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'inventario' => $inventario
                ]);
            }

            // Get the list of sucursales
            $sucursales = $this->getSucursales();

            // Verify if we got actual data
            if (empty($inventario)) {
                Log::warning('No se encontraron datos de inventario', [
                    'nombre_sucursal' => $nombreSucursal
                ]);
                return view('InventariosProductos', compact('inventario', 'sucursales', 'nombreSucursal'))
                    ->with('warning', 'No se encontraron productos en el inventario');
            }

            return view('InventariosProductos', compact('inventario', 'sucursales', 'nombreSucursal'));

        } catch (\Exception $e) {
            Log::error('Error al listar inventario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al listar el inventario: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al listar el inventario: ' . $e->getMessage());
        }
    }
    // Mostrar formulario para crear un producto en inventario
    public function mostrarCrearProductoInventario()
    {
        try {
            $responseProductos = Http::get('http://127.0.0.1:3000/productos');
            if ($responseProductos->failed() || !$responseProductos->json('data')) {
                Log::error('Fallo al obtener productos:', ['status' => $responseProductos->status(), 'body' => $responseProductos->body()]);
                return redirect()->route('inventario.listar')->with('error', 'Error al cargar los productos');
            }
            $productos = $responseProductos->json('data');

            $responseSucursales = Http::get('http://127.0.0.1:3000/sucursales');
            if ($responseSucursales->failed() || !$responseSucursales->json('data')) {
                Log::error('Fallo al obtener sucursales:', ['status' => $responseSucursales->status(), 'body' => $responseSucursales->body()]);
                return redirect()->route('inventario.listar')->with('error', 'Error al cargar las sucursales');
            }
            $sucursales = $responseSucursales->json('data');

            return view('CrearProductoInventario', compact('productos', 'sucursales'));
        } catch (\Exception $e) {
            Log::error('Error al cargar el formulario de creación: ' . $e->getMessage());
            return redirect()->route('inventario.listar')->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    // Insertar producto en inventario
    public function insertarProductoInventario(Request $request)
{
    try {
        // Validaciones del servidor
        $validated = $request->validate([
            'COD_PRODUCTO' => 'required|integer|min:1',
            'COD_SUCURSAL' => 'required|integer|min:1',
            'CANTIDAD' => 'required|numeric|min:0|max:10000',
            'STOCK_MINIMO' => 'required|numeric|min:0|max:10000',
            'PRECIO_VENTA' => 'required|numeric|min:0|max:10000',
        ]);

        // No necesitas buscar el CODIGO del producto aquí porque ya lo tienes como COD_PRODUCTO desde el formulario
        $data = [
            'COD_PRODUCTO' => $request->COD_PRODUCTO, // Cambiar CODIGO por COD_PRODUCTO
            'COD_SUCURSAL' => $request->COD_SUCURSAL,
            'CANTIDAD' => $request->CANTIDAD,
            'STOCK_MINIMO' => $request->STOCK_MINIMO,
            'PRECIO_VENTA' => $request->PRECIO_VENTA,
        ];

        // Enviar solicitud a la API de Node.js
        $response = Http::post('http://127.0.0.1:3000/inventario/productos', $data);
        if ($response->failed()) {
            Log::error('Fallo al insertar producto en inventario:', ['status' => $response->status(), 'body' => $response->body()]);
            return redirect()->back()->with('error', 'Error al agregar al inventario: ' . $response->body())->withInput();
        }

        return redirect()->route('inventario.listar')->with('success', 'Producto agregado al inventario exitosamente');
    } catch (\Exception $e) {
        Log::error('Error al insertar producto en inventario: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al agregar al inventario: ' . $e->getMessage())->withInput();
    }
}

public function agregarStockProducto(Request $request)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('PUT', 'http://localhost:3000/inventario/productos/agregar-stock', [
        'json' => [
            'CODIGO_PRODUCTO' => $request->input('cod_producto'), // Ahora es una cadena
            'COD_SUCURSAL' => (int) $request->input('cod_sucursal'), // Sigue siendo un entero
            'CANTIDAD' => (float) $request->input('cantidad'), // Convertimos a float
        ]
    ]);

    $data = json_decode($response->getBody(), true);

    if ($data['success']) {
        return redirect()->route('inventario.listar')->with('success', $data['message']);
    } else {
        return back()->withErrors(['error' => $data['message']]);
    }
}

public function mostrarActualizarInventarioProducto($codigo, $cod_sucursal)
{
    try {
        $responseInventario = Http::get('http://127.0.0.1:3000/inventario-productos', ['nombre_sucursal' => null]);
        if ($responseInventario->failed() || !$responseInventario->json('data')) {
            return redirect()->route('inventario.listar')->with('error', 'Error al cargar el inventario');
        }

        $inventario = collect($responseInventario->json('data'))
            ->firstWhere(function ($item) use ($codigo, $cod_sucursal) {
                return $item['CODIGO_PRODUCTO'] === $codigo && $item['COD_SUCURSAL'] == $cod_sucursal;
            });

        if (!$inventario) {
            return redirect()->route('inventario.listar')->with('error', 'Producto no encontrado en la sucursal especificada');
        }

        return view('ActualizarProductoInventario', compact('inventario'));
    } catch (\Exception $e) {
        Log::error('Error al cargar el formulario de actualización: ' . $e->getMessage());
        return redirect()->route('inventario.listar')->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
    }
}

// Nuevo método para actualizar el inventario
public function actualizarInventarioProducto(Request $request)
{
    try {
        $validated = $request->validate([
            'CODIGO' => 'required|string|max:15',
            'COD_SUCURSAL' => 'required|integer|min:1',
            'STOCK_MINIMO' => 'required|numeric|min:0|max:10000',
            'PRECIO_VENTA' => 'required|numeric|min:0|max:10000',
        ]);

        // Preparar datos para la API
        $data = [
            'CODIGO' => $request->CODIGO,
            'COD_SUCURSAL' => (int) $request->COD_SUCURSAL, // Asegurar tipo entero
            'STOCK_MINIMO' => floatval($request->STOCK_MINIMO), // Asegurar tipo float
            'PRECIO_VENTA' => floatval($request->PRECIO_VENTA), // Asegurar tipo float
        ];

        // Enviar solicitud a la API de Node.js
        $response = Http::put('http://127.0.0.1:3000/inventario/productos', $data);

        if ($response->failed()) {
            Log::error('Fallo al actualizar producto en inventario:', ['status' => $response->status(), 'body' => $response->body()]);
            return redirect()->back()->with('error', 'Error al actualizar el inventario: ' . $response->json('message'))->withInput();
        }

        return redirect()->route('inventario.listar')->with('success', 'Inventario actualizado exitosamente');
    } catch (\Exception $e) {
        Log::error('Error al actualizar inventario: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al actualizar el inventario: ' . $e->getMessage())->withInput();
    }
}
public function realizarTraslado(Request $request)
{
    try {
        Log::info('Solicitud recibida para realizar traslado', $request->all());

        // Validaciones
        $validated = $request->validate([
            'nombre_sucursal_origen' => 'required|string|max:50',
            'nombre_sucursal_destino' => 'required|string|max:50',
            'fecha_traslado' => 'required|date',
            'notas' => 'nullable|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.codigo_producto' => 'required|string|max:15',
            'productos.*.cantidad' => 'required|numeric|min:1|max:10000',
        ]);

        // Validar que las sucursales no sean iguales
        if ($request->nombre_sucursal_origen === $request->nombre_sucursal_destino) {
            Log::warning('Sucursales iguales detectadas', [
                'origen' => $request->nombre_sucursal_origen,
                'destino' => $request->nombre_sucursal_destino
            ]);
            return redirect()->back()->with('error', 'La sucursal de origen y destino no pueden ser la misma.')->withInput();
        }

        // Obtener el COD_USUARIO de la sesión
        $cod_usuario = $request->session()->get('cod_usuario');
        if (!$cod_usuario) {
            Log::error('No se encontró COD_USUARIO en la sesión');
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar un traslado');
        }

        // Consultar el token en la tabla SESIONES
        $token = DB::table('SESIONES')
            ->where('COD_USUARIO', $cod_usuario)
            ->where('FECHA_EXPIRACION', '>', now())
            ->orderBy('FECHA_CREACION', 'desc')
            ->value('TOKEN');

        if (!$token) {
            Log::error('No se encontró un token válido en SESIONES para COD_USUARIO: ' . $cod_usuario);
            return redirect()->route('login')->with('error', 'Sesión expirada o no encontrada. Por favor, inicia sesión nuevamente.');
        }

        // Obtener sucursales
        $responseSucursales = Http::get('http://127.0.0.1:3000/sucursales');
        if ($responseSucursales->failed() || !$responseSucursales->json('data')) {
            Log::error('Fallo al obtener sucursales:', ['status' => $responseSucursales->status(), 'body' => $responseSucursales->body()]);
            return redirect()->back()->with('error', 'Error al obtener las sucursales.')->withInput();
        }
        $sucursales = $responseSucursales->json('data', []);
        $sucursalOrigen = collect($sucursales)->firstWhere('NOMBRE_SUCURSAL', $request->nombre_sucursal_origen);
        $sucursalDestino = collect($sucursales)->firstWhere('NOMBRE_SUCURSAL', $request->nombre_sucursal_destino);

        if (!$sucursalOrigen || !$sucursalDestino) {
            Log::error('Sucursal no encontrada:', [
                'origen' => $request->nombre_sucursal_origen,
                'destino' => $request->nombre_sucursal_destino
            ]);
            return redirect()->back()->with('error', 'Sucursal de origen o destino no encontrada.')->withInput();
        }

        // Obtener productos
        $responseProductos = Http::get('http://127.0.0.1:3000/productos');
        if ($responseProductos->failed() || !$responseProductos->json('data')) {
            Log::error('Fallo al obtener productos:', ['status' => $responseProductos->status(), 'body' => $responseProductos->body()]);
            return redirect()->back()->with('error', 'Error al obtener los productos.')->withInput();
        }
        $productos = $responseProductos->json('data', []);

        // Iterar sobre los productos y realizar los traslados
        foreach ($request->productos as $producto) {
            $productoData = collect($productos)->firstWhere('CODIGO', $producto['codigo_producto']);
            if (!$productoData) {
                Log::error('Producto no encontrado:', ['codigo' => $producto['codigo_producto']]);
                return redirect()->back()->with('error', 'Producto no encontrado: ' . $producto['codigo_producto'])->withInput();
            }

            $data = [
                'cod_sucursal_origen' => (int)$sucursalOrigen['COD_SUCURSAL'],
                'cod_sucursal_destino' => (int)$sucursalDestino['COD_SUCURSAL'],
                'codigo_producto' => (int)$productoData['COD_PRODUCTO'],
                'cantidad' => floatval($producto['cantidad']),
                'fecha_traslado' => $request->fecha_traslado,
                'notas' => $request->notas,
            ];

            Log::info('Enviando solicitud de traslado a la API', $data);

            // Enviar solicitud a la API de Node.js con el token de SESIONES
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post('http://127.0.0.1:3000/traslados', $data);

            if ($response->failed() || !$response->json('success')) {
                Log::error('Fallo al realizar traslado', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->back()->with('error', $response->json('message', 'Error al realizar el traslado'))->withInput();
            }
        }

        return redirect()->route('traslados.historial')->with('success', 'Traslado realizado exitosamente');
    } catch (\Exception $e) {
        Log::error('Excepción al realizar traslado: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Error al realizar el traslado: ' . $e->getMessage())->withInput();
    }
}

public function mostrarFormularioTraslado()
{
    try {
        // Obtener sucursales
        $responseSucursales = Http::get('http://127.0.0.1:3000/sucursales');
        if ($responseSucursales->failed() || !$responseSucursales->json('data')) {
            Log::error('Fallo al obtener sucursales:', ['status' => $responseSucursales->status(), 'body' => $responseSucursales->body()]);
            return redirect()->route('inventario.listar')->with('error', 'Error al cargar las sucursales');
        }
        $sucursales = $responseSucursales->json('data');

        // Obtener productos
        $responseProductos = Http::get('http://127.0.0.1:3000/productos');
        if ($responseProductos->failed() || !$responseProductos->json('data')) {
            Log::error('Fallo al obtener productos:', ['status' => $responseProductos->status(), 'body' => $responseProductos->body()]);
            return redirect()->route('inventario.listar')->with('error', 'Error al cargar los productos');
        }
        $productos = $responseProductos->json('data');

        return view('Traslados', compact('sucursales', 'productos'));
    } catch (\Exception $e) {
        Log::error('Error al cargar el formulario de traslado: ' . $e->getMessage());
        return redirect()->route('inventario.listar')->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
    }
}

public function mostrarHistorialTraslados()
{
    try {
        // Obtener traslados
        $responseTraslados = Http::get('http://127.0.0.1:3000/traslados');
        if ($responseTraslados->failed() || !$responseTraslados->json('data')) {
            Log::error('Fallo al obtener traslados:', ['status' => $responseTraslados->status(), 'body' => $responseTraslados->body()]);
            return redirect()->route('traslados.formulario')->with('error', 'Error al cargar los traslados');
        }
        $traslados = $responseTraslados->json('data', []);

        // Obtener sucursales
        $responseSucursales = Http::get('http://127.0.0.1:3000/sucursales');
        if ($responseSucursales->failed() || !$responseSucursales->json('data')) {
            Log::error('Fallo al obtener sucursales:', ['status' => $responseSucursales->status(), 'body' => $responseSucursales->body()]);
            return redirect()->route('traslados.formulario')->with('error', 'Error al cargar las sucursales');
        }
        $sucursales = $responseSucursales->json('data', []);

        return view('HistorialTraslados', compact('traslados', 'sucursales'));
    } catch (\Exception $e) {
        Log::error('Error al cargar el historial de traslados: ' . $e->getMessage());
        return redirect()->route('traslados.formulario')->with('error', 'Error al cargar el historial: ' . $e->getMessage());
    }
}

}







