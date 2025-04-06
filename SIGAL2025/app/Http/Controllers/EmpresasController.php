<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class EmpresasController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout'  => 5.0,
        ]);
    }

    public function mostrarDatosEmpresa()
    {
        try {
            $response = $this->client->get('/api/empresa');
            $empresa = json_decode($response->getBody()->getContents(), true)['data'] ?? [];
            if (empty($empresa)) {
                return redirect()->route('empresa.mostrar')->with('error', 'No se encontraron datos de empresa');
            }
            return view('Empresas', compact('empresa'));
        } catch (RequestException $e) {
            $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            Log::error('Error al mostrar datos de empresa: ' . $errorMessage);
            return redirect()->route('empresa.mostrar')->with('error', 'Error al cargar los datos');
        }
    }
    
    public function actualizarDatosEmpresa(Request $request, $cod_empresa)
{
    \Illuminate\Support\Facades\Log::debug('Entrando a actualizarDatosEmpresa', ['cod_empresa' => $cod_empresa]);

    try {
        $validatedData = $request->validate([
            'razon_social' => 'required|string|max:50|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'nombre_comercial' => 'required|string|max:20|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'rtn' => 'required|string|max:20|regex:/^[0-9-]+$/',
            'regimen_fiscal' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'actividad_economica' => 'required|string|max:50|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'direccion' => 'required|string|max:50|regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/',
            'ciudad' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'departamento' => 'required|string|max:50|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'telefono' => 'required|string|max:15|regex:/^[0-9-]+$/',
            'email' => 'required|email|max:30',
            'sitio_web' => 'nullable|url|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        \Illuminate\Support\Facades\Log::info('Validación pasada', $validatedData);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Illuminate\Support\Facades\Log::error('Error de validación', $e->errors());
        throw $e; // Re-lanza la excepción para que Laravel maneje la redirección
    }

    $data = $request->except(['logo', '_token', '_method']);
    \Illuminate\Support\Facades\Log::info('Datos enviados a Node.js:', $data);

    try {
        $response = $this->client->put("/api/empresa/{$cod_empresa}", [
            'json' => $data
        ]);

        $responseBody = $response->getBody()->getContents();
        \Illuminate\Support\Facades\Log::info('Respuesta de Node.js:', [
            'status' => $response->getStatusCode(),
            'body' => $responseBody
        ]);

        if ($response->getStatusCode() === 200) {
            return redirect()->route('empresa.mostrar')->with('success', 'Datos actualizados exitosamente');
        }

        return redirect()->back()->with('error', 'Error al actualizar los datos');
    } catch (\GuzzleHttp\Exception\RequestException $e) {
        $errorMessage = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
        \Illuminate\Support\Facades\Log::error('Error al actualizar datos de empresa: ' . $errorMessage);
        return redirect()->back()->with('error', 'Error al actualizar los datos: ' . $errorMessage);
    }
}
}