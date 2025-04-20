<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
 * Mostrar la lista de facturas
 */
public function index()
{
    try {
        // Obtener la lista de facturas
        $response = $this->client->get('/api/facturas', [
            'headers' => [
                'Authorization' => 'Bearer ' . session('api_token', ''),
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        Log::info('Respuesta de /api/facturas:', $responseBody);

        if ($response->getStatusCode() !== 200 || !isset($responseBody['success']) || !$responseBody['success']) {
            return redirect()->route('dashboard')->with('error', 'No se pudieron obtener las facturas');
        }

        $facturas = $responseBody['data'];

        // Renderizar la vista para la lista de facturas
        return view('ListarFacturasVenta', [
            'facturas' => $facturas
        ]);

    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al obtener la lista de facturas: ' . $errorMessage);
        return redirect()->route('dashboard')->with('error', $errorMessage);
    } catch (\Exception $e) {
        Log::error('Error general al obtener la lista de facturas: ' . $e->getMessage());
        return redirect()->route('dashboard')->with('error', $e->getMessage());
    }
}
    /**
     * Mostrar formulario para crear factura
     */
    public function create()
    {
        try {
            $headers = ['Authorization' => 'Bearer ' . session('api_token', '')];

            // Obtener tipos de documento
            $responseTipos = $this->client->get('/api/tipos-documento', [
                'headers' => $headers,
                'query' => ['estado' => 'ACTIVO']
            ]);
            $tiposBody = json_decode($responseTipos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/tipos-documento:', $tiposBody);
            if (!isset($tiposBody['success']) || !$tiposBody['success'] || empty($tiposBody['data'])) {
                $errorMessage = $tiposBody['error'] ?? 'No se encontraron tipos de documento';
                Log::error('Error en /api/tipos-documento: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar tipos de documento: ' . $errorMessage);
            }
            $tiposDocumento = $tiposBody['data'];

            // Obtener puntos de emisión
            $responsePuntos = $this->client->get('/api/puntos-emision', [
                'headers' => $headers,
                'query' => ['estado' => 'ACTIVO']
            ]);
            $puntosBody = json_decode($responsePuntos->getBody()->getContents(), true);
            Log::info('Respuesta de /api/puntos-emision:', $puntosBody);
            if (!isset($puntosBody['success']) || !$puntosBody['success'] || empty($puntosBody['data'])) {
                $errorMessage = $puntosBody['error'] ?? 'No se encontraron puntos de emisión';
                Log::error('Error en /api/puntos-emision: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar puntos de emisión: ' . $errorMessage);
            }
            $puntosEmision = $puntosBody['data'];

            // Obtener sucursales
            $responseSucursales = $this->client->get('/sucursales', [
                'headers' => $headers,
                'query' => ['estado' => 'ACTIVA']
            ]);
            $sucursalesBody = json_decode($responseSucursales->getBody()->getContents(), true);
            Log::info('Respuesta de /sucursales:', $sucursalesBody);
            if (!isset($sucursalesBody['success']) || !$sucursalesBody['success'] || empty($sucursalesBody['data'])) {
                $errorMessage = $sucursalesBody['error'] ?? 'No se encontraron sucursales';
                Log::error('Error en /sucursales: ' . $errorMessage);
                return redirect()->back()->with('error', 'Error al cargar sucursales: ' . $errorMessage);
            }
            $sucursales = $sucursalesBody['data'];

            // Pasar datos a la vista
            return view('FacturasVenta', compact('tiposDocumento', 'puntosEmision', 'sucursales'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al cargar formulario de factura: ' . $errorMessage);
            return redirect()->back()->with('error', 'Error al cargar el formulario: ' . $errorMessage);
        }
    }

 /**
 * Crear factura de venta
 */
public function crearFacturaVenta(Request $request)
{
    try {
        $validated = $request->validate([
            'cod_cliente' => 'nullable|integer',
            'cod_sucursal' => 'required|integer',
            'cod_empleado' => 'required|integer',
            'impuesto' => 'required|numeric',
            'descuento' => 'required|numeric',
            'metodo_pago' => 'required|string',
            'cod_tipo_documento' => 'required|integer',
            'cod_punto_emision' => 'required|integer',
            'productos' => 'required|json'
        ]);

        $data = $validated;
        $productos = json_decode($data['productos'], true);
        unset($data['productos']); // Quita los productos para la primera petición

        Log::info('Datos enviados para crear factura:', $data);

        // 1. Crear la factura
        $response = $this->client->post('/api/facturas/venta', [
            'headers' => [
                'Authorization' => 'Bearer ' . session('api_token', ''),
                'Content-Type' => 'application/json',
            ],
            'json' => $data
        ]);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        Log::info('Respuesta al crear factura:', $responseBody);

        if ($response->getStatusCode() !== 201 || !isset($responseBody['data']['cod_factura'])) {
            throw new \Exception('No se pudo crear la factura');
        }

        $cod_factura = $responseBody['data']['cod_factura'];
        $numero_factura = $responseBody['data']['numero_factura'];
        $numero_fiscal = $responseBody['data']['numero_fiscal'];

        // 2. Agregar los productos
        foreach ($productos as $producto) {
            $productoData = [
                'codigo_producto' => $producto['codigo_producto'],
                'cantidad' => $producto['cantidad'],
                'precio_override' => $producto['precio'] ?? null
            ];

            $productoResponse = $this->client->post("/api/facturas/venta/{$cod_factura}/productos", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('api_token', ''),
                    'Content-Type' => 'application/json',
                ],
                'json' => $productoData
            ]);

            Log::info("Producto agregado a factura {$cod_factura}:", [
                'producto' => $productoData,
                'response' => json_decode($productoResponse->getBody()->getContents(), true)
            ]);
        }

        // 3. Finalizar la factura usando PUT
        $finalizarResponse = $this->client->put("/api/facturas/venta/{$cod_factura}/finalizar", [
            'headers' => [
                'Authorization' => 'Bearer ' . session('api_token', ''),
                'Content-Type' => 'application/json',
            ],
            'json' => ['metodo_pago' => $data['metodo_pago']]
        ]);

        $finalizarBody = json_decode($finalizarResponse->getBody()->getContents(), true);
        Log::info("Factura {$cod_factura} finalizada:", [
            'response' => $finalizarBody
        ]);

        if ($finalizarResponse->getStatusCode() !== 200 || !isset($finalizarBody['success']) || !$finalizarBody['success']) {
            throw new \Exception($finalizarBody['error'] ?? 'No se pudo finalizar la factura');
        }

        // 4. Obtener los datos de la factura para el resumen
        $facturaResponse = $this->client->get("/api/facturas/{$cod_factura}/impresion", [
            'headers' => [
                'Authorization' => 'Bearer ' . session('api_token', ''),
                'Content-Type' => 'application/json',
            ],
        ]);

        $facturaData = json_decode($facturaResponse->getBody()->getContents(), true);
        Log::info("Datos de la factura {$cod_factura} para impresión:", $facturaData);

        if ($facturaResponse->getStatusCode() !== 200 || !isset($facturaData['success']) || !$facturaData['success']) {
            throw new \Exception('No se pudieron obtener los datos de la factura');
        }

        // Preparar los datos para la vista
        $factura = [
            'cod_factura' => $cod_factura,
            'numero_factura' => $numero_factura,
            'numero_fiscal' => $numero_fiscal,
            'cliente' => $facturaData['data']['DATOS_CLIENTE'][0]['NOMBRE_CLIENTE'],
            'total' => $facturaData['data']['TOTALES'][0]['TOTAL'],
            'metodo_pago' => $facturaData['data']['TOTALES'][0]['FORMA_PAGO'],
            'fecha' => $facturaData['data']['DATOS_FACTURA'][0]['FECHA'],
        ];

        // 5. Redirigir a la vista de éxito
        return view('FacturasVentaExito', [
            'factura' => (object) $factura
        ]);

    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al crear factura: ' . $errorMessage);
        return redirect()->route('facturas.create')->with('error', $errorMessage);
    } catch (\Exception $e) {
        Log::error('Error general al crear factura: ' . $e->getMessage());
        return redirect()->route('facturas.create')->with('error', $e->getMessage());
    }
}
/**
 * Mostrar los detalles de una factura
 */
public function show($cod_factura)
{
    try {
        // Obtener los datos de la factura
        $facturaResponse = $this->client->get("/api/facturas/{$cod_factura}/impresion", [
            'headers' => [
                'Authorization' => 'Bearer ' . session('api_token', ''),
                'Content-Type' => 'application/json',
            ],
        ]);

        $facturaData = json_decode($facturaResponse->getBody()->getContents(), true);
        Log::info("Datos de la factura {$cod_factura} para impresión:", $facturaData);

        if ($facturaResponse->getStatusCode() !== 200 || !isset($facturaData['success']) || !$facturaData['success']) {
            Log::error("Error: No se pudieron obtener los datos de la factura. Estado: {$facturaResponse->getStatusCode()}");
            return redirect()->route('facturas.index')->with('error', 'No se pudieron obtener los datos de la factura');
        }

        // Verificar si las secciones esperadas están presentes
        if (!isset($facturaData['data']['DATOS_EMPRESA'][0]) ||
            !isset($facturaData['data']['DATOS_FACTURA'][0]) ||
            !isset($facturaData['data']['DATOS_CLIENTE'][0]) ||
            !isset($facturaData['data']['DATOS_CAI'][0]) ||
            !isset($facturaData['data']['DETALLE_PRODUCTOS']) ||
            !isset($facturaData['data']['TOTALES'][0]) ||
            !isset($facturaData['data']['LEYENDAS_FISCALES'][0])) {
            Log::error("Error: Faltan secciones esperadas en los datos de la factura:", $facturaData['data']);
            return redirect()->route('facturas.index')->with('error', 'Los datos de la factura están incompletos');
        }

        // Preparar los datos para la vista, convirtiendo cada subsección a objeto
        $factura = [
            'empresa' => (object) $facturaData['data']['DATOS_EMPRESA'][0],
            'factura' => (object) $facturaData['data']['DATOS_FACTURA'][0],
            'cliente' => (object) $facturaData['data']['DATOS_CLIENTE'][0],
            'cai' => (object) $facturaData['data']['DATOS_CAI'][0],
            'productos' => array_map(function ($producto) {
                return (object) $producto;
            }, $facturaData['data']['DETALLE_PRODUCTOS']),
            'totales' => (object) $facturaData['data']['TOTALES'][0],
            'leyendas' => (object) $facturaData['data']['LEYENDAS_FISCALES'][0],
        ];

        Log::info("Datos preparados para la vista:", (array) $factura);

        // Renderizar la vista correcta
        return view('FacturaMostrar', [
            'factura' => (object) $factura
        ]);

    } catch (RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        Log::error('Error al obtener los datos de la factura: ' . $errorMessage);
        return redirect()->route('facturas.index')->with('error', $errorMessage);
    } catch (\Exception $e) {
        Log::error('Error general al obtener los datos de la factura: ' . $e->getMessage());
        return redirect()->route('facturas.index')->with('error', $e->getMessage());
    }
}
    /**
     * Agregar producto a factura (no usado en este flujo, pero mantenido por compatibilidad)
     */
    public function agregarProductoFactura(Request $request, $cod_factura)
    {
        $request->validate([
            'codigo_producto' => 'required|integer',
            'cantidad' => 'required|numeric|min:1',
            'precio_override' => 'nullable|numeric|min:0'
        ]);

        $data = $request->only(['codigo_producto', 'cantidad', 'precio_override']);
        Log::info('Datos enviados para agregar producto:', $data);

        try {
            $response = $this->client->post("/api/facturas/venta/{$cod_factura}/productos", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('api_token', ''),
                ],
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
     * Finalizar factura (no usado en este flujo, pero mantenido por compatibilidad)
     */
    public function finalizarFactura(Request $request, $cod_factura)
    {
        $request->validate([
            'metodo_pago' => 'required|string|in:EFECTIVO,TARJETA,TRANSFERENCIA'
        ]);

        $data = $request->only(['metodo_pago']);
        Log::info('Datos enviados para finalizar factura:', $data);

        try {
            $response = $this->client->put("/api/facturas/venta/{$cod_factura}/finalizar", [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('api_token', ''),
                ],
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

    public function getClientes(Request $request)
    {
        \Log::info('Solicitud recibida en getClientes', [
            'session_id' => session()->getId(),
            'user' => Auth::check() ? Auth::user()->toArray() : 'No autenticado',
            'query' => $request->query(),
            'headers' => $request->headers->all()
        ]);

        try {
            $response = $this->client->get('/clientes', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . session('api_token', ''),
                ],
                'query' => [
                    'nombre' => $request->query('search'),
                    'numero_identidad' => $request->query('numero_identidad'),
                    'cod_cliente' => $request->query('cod_cliente'),
                ]
            ]);
            $responseBody = $response->getBody()->getContents();
            \Log::info('Respuesta cruda de /clientes: ' . $responseBody);
            $data = json_decode($responseBody, true);
            \Log::info('Respuesta parseada de /clientes:', $data);

            if (empty($data)) {
                return response()->json(['message' => 'No se encontraron clientes'], 200);
            }

            return response()->json($data);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorMessage = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            \Log::error('Error al cargar clientes: ' . $errorMessage);
            return response()->json(['error' => 'Error al cargar clientes: ' . $errorMessage], 500);
        } catch (\Exception $e) {
            \Log::error('Error inesperado al cargar clientes: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener lista de productos desde el API
     */
    public function getProductos(Request $request)
    {
        try {
            $response = $this->client->get('/inventario-productos', [
                'headers' => ['Authorization' => 'Bearer ' . session('api_token', '')],
                'query' => [
                    'nombre_sucursal' => $request->query('nombre_sucursal'),
                ]
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de /inventario-productos:', $responseBody);

            if (!isset($responseBody['success']) || !$responseBody['success']) {
                throw new \Exception($responseBody['message'] ?? 'Error al obtener productos');
            }

            return response()->json($responseBody);
        } catch (\Exception $e) {
            Log::error('Error al cargar productos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar productos: ' . $e->getMessage()], 500);
        }
    }
}