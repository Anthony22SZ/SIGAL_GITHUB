<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class FacturasVentasController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 10.0,
            'connect_timeout' => 5.0,
        ]);
    }

    /**
     * Listar facturas
     */
    public function index(Request $request)
    {
        try {
            $response = $this->client->get('/api/facturas', [
                'query' => [
                    'numero_factura' => $request->query('numero_factura'),
                    'numero_fiscal' => $request->query('numero_fiscal'),
                    'nombre_cliente' => $request->query('nombre_cliente'),
                    'fecha_inicio' => $request->query('fecha_inicio'),
                    'fecha_fin' => $request->query('fecha_fin'),
                    'estado' => $request->query('estado'),
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de /api/facturas:', $responseBody);

            if (!isset($responseBody['success']) || !$responseBody['success']) {
                $errorMessage = $responseBody['error'] ?? 'Error desconocido al obtener facturas';
                Log::error('Error en /api/facturas: ' . $errorMessage);
                return view('facturas.index')->with('error', 'Error al cargar las facturas: ' . $errorMessage);
            }

            $facturas = $responseBody['data'] ?? [];
            return view('facturas.index', compact('facturas'));

        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al listar facturas: ' . $errorMessage);
            return view('facturas.index')->with('error', 'Error al cargar las facturas: ' . $errorMessage);
        }
    }

    /**
     * Mostrar formulario para crear factura
     */
    

public function create()
{
    try {
        // Obtener tipos de documento
        $responseTipos = $this->client->get('/api/tipos-documento', ['query' => ['estado' => 'ACTIVO']]);
        $tiposBody = json_decode($responseTipos->getBody()->getContents(), true);
        Log::info('Respuesta de /api/tipos-documento:', $tiposBody);
        if (!isset($tiposBody['success']) || !$tiposBody['success']) {
            $errorMessage = $tiposBody['error'] ?? 'Error desconocido al obtener tipos de documento';
            Log::error('Error en /api/tipos-documento: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar tipos de documento: ' . $errorMessage);
        }
        $tiposDocumento = $tiposBody['data'] ?? [];

        // Obtener puntos de emisión
        $responsePuntos = $this->client->get('/api/puntos-emision', ['query' => ['estado' => 'ACTIVO']]);
        $puntosBody = json_decode($responsePuntos->getBody()->getContents(), true);
        Log::info('Respuesta de /api/puntos-emision:', $puntosBody);
        if (!isset($puntosBody['success']) || !$puntosBody['success']) {
            $errorMessage = $puntosBody['error'] ?? 'Error desconocido al obtener puntos de emisión';
            Log::error('Error en /api/puntos-emision: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar puntos de emisión: ' . $errorMessage);
        }
        $puntosEmision = $puntosBody['data'] ?? [];

        // Obtener sucursales
        $responseSucursales = $this->client->get('/sucursales', ['query' => ['estado' => 'ACTIVA']]);
        $sucursalesBody = json_decode($responseSucursales->getBody()->getContents(), true);
        Log::info('Respuesta de /sucursales:', $sucursalesBody);
        if (!isset($sucursalesBody['success']) || !$sucursalesBody['success']) {
            $errorMessage = $sucursalesBody['error'] ?? 'Error desconocido al obtener sucursales';
            Log::error('Error en /sucursales: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar sucursales: ' . $errorMessage);
        }
        $sucursales = $sucursalesBody['data'] ?? [];

        // Obtener clientes desde el endpoint /clientes
        $responseClientes = $this->client->get('/clientes');
        $clientes = json_decode($responseClientes->getBody()->getContents(), true);
        Log::info('Respuesta de /clientes:', $clientes);

        // Verificar si la respuesta de clientes es válida
        if (!is_array($clientes)) {
            $clientes = [];
            Log::warning('No se encontraron clientes o respuesta inválida desde /clientes');
        }

        // Obtener productos desde el endpoint /inventario-productos
        $responseProductos = $this->client->get('/inventario-productos');
        $productosBody = json_decode($responseProductos->getBody()->getContents(), true);
        Log::info('Respuesta de /inventario-productos:', $productosBody);

        // Verificar si la respuesta de productos es válida
        if (!isset($productosBody['success']) || !$productosBody['success']) {
            Log::error('Error al obtener productos desde /inventario-productos: ' . ($productosBody['error'] ?? 'Error desconocido'));
            $productos = [];
        } else {
            $productos = $productosBody['data'] ?? [];
        }

        // Pasar todos los datos a la vista
        return view('FacturasVenta', compact('tiposDocumento', 'puntosEmision', 'sucursales', 'clientes', 'productos'));
    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al cargar formulario de factura: ' . $errorMessage);
        return redirect()->back()->with('error', 'Error al cargar el formulario: ' . $errorMessage);
    }
}

    /**
     * Crear una nueva factura
     */
    public function crearFacturaVenta(Request $request)
    {
        $request->validate([
            'cod_cliente' => 'nullable|integer',
            'cod_sucursal' => 'required|integer',
            'cod_empleado' => 'required|integer',
            'impuesto' => 'required|numeric',
            'descuento' => 'required|numeric',
            'metodo_pago' => 'required|string',
            'cod_tipo_documento' => 'required|integer',
            'cod_punto_emision' => 'required|integer',
            'productos' => 'required|json',
        ]);

        $data = $request->only([
            'cod_cliente', 'cod_sucursal', 'cod_empleado', 'impuesto',
            'descuento', 'metodo_pago', 'cod_tipo_documento', 'cod_punto_emision'
        ]);
        Log::info('Datos enviados para crear factura:', $data);

        try {
            $response = $this->client->post('/api/facturas/venta', [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al crear factura:', $responseBody);

            if ($response->getStatusCode() === 201) {
                $cod_factura = $responseBody['data']['cod_factura'];

                $productos = json_decode($request->input('productos'), true);
                foreach ($productos as $producto) {
                    $productoData = [
                        'codigo_producto' => $producto['codigo_producto'],
                        'cantidad' => $producto['cantidad'],
                        'precio_override' => $producto['precio']
                    ];
                    $this->client->post("/api/facturas/venta/{$cod_factura}/productos", [
                        'json' => $productoData
                    ]);
                }

                return redirect()->route('facturas.show', ['cod_factura' => $cod_factura])
                    ->with('success', $responseBody['message'] ?? 'Factura creada exitosamente');
            }

            $errorMessage = $responseBody['error'] ?? 'Error desconocido al crear la factura';
            Log::error('Error al crear factura: ' . $errorMessage);
            return redirect()->back()->with('error', $errorMessage)->withInput();

        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al crear factura: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al crear la factura: ' . $errorMessage)->withInput();
        }
    }

    /**
     * Mostrar detalles de factura y formulario para agregar productos
     */
    public function show($cod_factura)
    {
        try {
            $response = $this->client->get("/api/facturas/{$cod_factura}/impresion");
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de /api/facturas/{cod_factura}/impresion:', $responseBody);

            if (!isset($responseBody['success']) || !$responseBody['success']) {
                $errorMessage = $responseBody['error'] ?? 'Error desconocido al obtener datos de la factura';
                Log::error('Error en /api/facturas/{cod_factura}/impresion: ' . $errorMessage);
                return redirect()->route('facturas.index')->with('error', 'Error al cargar la factura: ' . $errorMessage);
            }

            $facturaData = $responseBody['data'] ?? [];
            return view('facturas.show', compact('facturaData', 'cod_factura'));

        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al mostrar factura: ' . $errorMessage);
            return redirect()->route('facturas.index')->with('error', 'Error al cargar la factura: ' . $errorMessage);
        }
    }

    /**
     * Agregar producto a factura
     */
    public function agregarProductoFactura(Request $request, $cod_factura)
    {
        $request->validate([
            'codigo_producto' => 'required|integer',
            'cantidad' => 'required|numeric',
            'precio_override' => 'nullable|numeric'
        ]);

        $data = $request->only(['codigo_producto', 'cantidad', 'precio_override']);
        Log::info('Datos enviados para agregar producto:', $data);

        try {
            $response = $this->client->post("/api/facturas/venta/{$cod_factura}/productos", [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al agregar producto:', $responseBody);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('facturas.show', ['cod_factura' => $cod_factura])
                    ->with('success', 'Producto agregado exitosamente');
            }

            $errorMessage = $responseBody['error'] ?? 'Error desconocido al agregar producto';
            Log::error('Error al agregar producto: ' . $errorMessage);
            return redirect()->back()->with('error', $errorMessage)->withInput();

        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al agregar producto: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al agregar producto: ' . $errorMessage)->withInput();
        }
    }

    /**
     * Finalizar factura
     */
    public function finalizarFactura(Request $request, $cod_factura)
    {
        $request->validate([
            'metodo_pago' => 'required|string'
        ]);

        $data = $request->only(['metodo_pago']);
        Log::info('Datos enviados para finalizar factura:', $data);

        try {
            $response = $this->client->put("/api/facturas/venta/{$cod_factura}/finalizar", [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta al finalizar factura:', $responseBody);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('facturas.index')
                    ->with('success', $responseBody['message'] ?? 'Factura finalizada exitosamente');
            }

            $errorMessage = $responseBody['error'] ?? 'Error desconocido al finalizar factura';
            Log::error('Error al finalizar factura: ' . $errorMessage);
            return redirect()->back()->with('error', $errorMessage)->withInput();

        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al finalizar factura: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al finalizar factura: ' . $errorMessage)->withInput();
        }
    }

    /**
     * Obtener datos de facturación (clientes y productos)
     */
    public function getDatosFacturacion()
{
    try {
        // Obtener clientes
        $responseClientes = $this->client->get('/clientes');
        $clientes = json_decode($responseClientes->getBody()->getContents(), true);
        Log::info('Respuesta de /clientes:', $clientes);

        // Obtener productos desde el endpoint /inventario-productos
        $responseProductos = $this->client->get('/inventario-productos');
        $productosBody = json_decode($responseProductos->getBody()->getContents(), true);
        Log::info('Respuesta de /inventario-productos:', $productosBody);

        // Verificar si la respuesta tiene el formato esperado
        if (!isset($productosBody['success']) || !$productosBody['success']) {
            $errorMessage = $productosBody['error'] ?? 'Error desconocido al obtener productos';
            Log::error('Error en /inventario-productos: ' . $errorMessage);
            return response()->json(['error' => 'Error al cargar los productos'], 500);
        }

        $productos = $productosBody['data'] ?? [];

        // Devolver ambos conjuntos de datos en una sola respuesta
        return response()->json([
            'clientes' => $clientes,
            'productos' => $productos
        ]);
    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al obtener datos de facturación: ' . $errorMessage);
        return response()->json(['error' => 'Error al cargar los datos'], 500);
    }
}
}