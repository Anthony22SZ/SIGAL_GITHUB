<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FacturaComprasController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:3000', // URL base de tu API de Node.js
        ]);
    }

    public function insertarFacturaCompraCompleta(Request $request)
    {
        $validatedData = $request->validate([
            'numero_factura' => 'required|string|max:50',
            'cod_proveedor' => 'required|integer',
            'impuesto' => 'required|numeric',
            'descuento' => 'numeric',
            'detalles' => 'required|array',
            'detalles.*.material_codigo' => 'required|string|max:50',
            'detalles.*.cantidad' => 'required|numeric',
            'detalles.*.precio' => 'required|numeric',
        ]);

        try {
            $response = $this->client->post('/facturas-compraCompleta', [
                'json' => $validatedData,
            ]);

            $data = json_decode($response->getBody(), true);

            return redirect()->route('FacturaCompra.listar')->with('success', 'Factura y Detalle creado exitosamente');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al insertar factura y detalles', 'error' => $e->getMessage()], 500);
        }

        

    }

     public function CrearFacturaCompra()
    {
        try {
            // Hacer la solicitud GET al servidor Node.js para obtener los materiales
            $responseMateriales = $this->client->get('/materiales');
            $responseDataMateriales = json_decode($responseMateriales->getBody()->getContents(), true);

            // Verificar si la respuesta tiene la estructura esperada
            if (isset($responseDataMateriales['success']) && $responseDataMateriales['success'] && isset($responseDataMateriales['data'])) {
                $materiales = $responseDataMateriales['data'];
            } else {
                $materiales = [];
            }
        } catch (\Exception $e) {
            // Capturar errores de la solicitud HTTP
            \Log::error('Error fetching materiales: ' . $e->getMessage());
            $materiales = [];
        }

        try {
            // Hacer la solicitud GET al servidor Node.js para obtener los proveedores
            $responseProveedores = $this->client->get('/proveedores');
            $responseDataProveedores = json_decode($responseProveedores->getBody()->getContents(), true);

            // Verificar si la respuesta tiene la estructura esperada
            if (isset($responseDataProveedores['success']) && $responseDataProveedores['success'] && isset($responseDataProveedores['data'])) {
                $proveedores = $responseDataProveedores['data'];
            } else {
                $proveedores = [];
            }
        } catch (\Exception $e) {
            // Capturar errores de la solicitud HTTP
            \Log::error('Error fetching proveedores: ' . $e->getMessage());
            $proveedores = [];
        }

        return view('crearFacturaCompra', compact('materiales', 'proveedores'));
    }

    public function listarFacturas(Request $request)
    {
        try {
            $response = $this->client->get('/facturas', [
                'query' => [
                    'proveedor' => $request->query('proveedor'),
                    'numero_factura' => $request->query('numero_factura'),
                ],
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            if ($data['success'] && isset($data['data'])) {
                $facturas = $data['data'];
            } else {
                $facturas = [];
            }
        } catch (\Exception $e) {
            \Log::error('Error al obtener facturas: ' . $e->getMessage());
            $facturas = [];
        }
    
        return view('Facturas', compact('facturas'));
    }

    public function verDetalleFactura(Request $request, $numero_factura)
{
    try {
        $response = $this->client->get('/detalle-compra', [
            'query' => [
                'numero_factura' => $numero_factura,
                'nombre_empresa' => $request->query('nombre_empresa'), // Opcional
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (is_array($data) && count($data) > 0) {
            return response()->json(['success' => true, 'detalles' => $data], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'No se encontraron detalles'], 404);
        }
    } catch (\Exception $e) {
        \Log::error('Error al obtener detalles de factura: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error al obtener detalles', 'error' => $e->getMessage()], 500);
    }
}
}