<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SucursalesController extends Controller
{
    private $apiBaseUrl = 'http://localhost:3000'; // URL base para todos los endpoints
    private $apiUrl = 'http://localhost:3000/sucursales'; // Específica para sucursales

    // Listar sucursales
    public function index(Request $request)
    {
        $nombre_sucursal = $request->query('nombre_sucursal');
        $nombre_empleado = $request->query('nombre_empleado');
        $cod_sucursal = $request->query('cod_sucursal');

        $response = Http::get($this->apiUrl, [
            'cod_sucursal' => $cod_sucursal,
            'nombre_sucursal' => $nombre_sucursal,
            'nombre_empleado' => $nombre_empleado
        ]);

        if ($response->successful()) {
            $sucursales = $response->json()['data'] ?? [];
            return view('Sucursales', compact('sucursales'));
        }

        return back()->withErrors(['error' => 'Error al obtener las sucursales']);
    }

    // Mostrar formulario para crear una nueva sucursal
    public function create()
    {
        $empleados = DB::select("SELECT COD_EMPLEADO, PRIMER_NOMBRE_E, PRIMER_APELLIDO_E FROM empleados");
        return view('CrearSucursal', compact('empleados'));
    }

    // Guardar nueva sucursal
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'cod_empleado' => 'nullable|integer'
        ]);

        $response = Http::post($this->apiUrl, [
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'cod_empleado' => $request->cod_empleado
        ]);

        if ($response->successful()) {
            return redirect()->route('sucursales.index')->with('success', 'Sucursal creada exitosamente.');
        }

        return back()->withErrors(['error' => 'Error al crear la sucursal']);
    }

    // Mostrar formulario de edición de una sucursal específica
    public function edit($cod_sucursal)
    {
        $response = Http::get($this->apiUrl, ['cod_sucursal' => $cod_sucursal]);

        if ($response->successful()) {
            $data = $response->json();
            $sucursal = $data['data'][0] ?? null;
            if (!$sucursal) {
                return redirect()->route('sucursales.index')->with('error', 'Sucursal no encontrada');
            }
            $empleados = DB::select("SELECT COD_EMPLEADO, PRIMER_NOMBRE_E, PRIMER_APELLIDO_E FROM empleados");
            return view('ActualizarSucursal', compact('sucursal', 'empleados'));
        }

        return redirect()->route('sucursales.index')->with('error', 'Error al obtener los detalles de la sucursal: ' . $response->status());
    }

    // Procesa la actualización de la sucursal
    public function update(Request $request, $cod_sucursal)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'cod_empleado' => 'required|integer'
        ]);

        try {
            $url = "{$this->apiBaseUrl}/actualizar-sucursal/{$cod_sucursal}";
            $data = [
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'cod_empleado' => (int) $request->cod_empleado
            ];
            $response = Http::put($url, $data);

            if ($response->successful()) {
                return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada exitosamente');
            }

            return back()->withErrors(['error' => 'Error al actualizar: ' . $response->status() . ' - ' . $response->body()]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Excepción: ' . $e->getMessage()]);
        }
    }

    // Cambiar el estado de la sucursal
    public function toggleState(Request $request, $cod_sucursal)
    {
        $currentState = $request->input('estado');
        $newState = $currentState === 'ACTIVA' ? 'INACTIVA' : 'ACTIVA';

        try {
            $url = "{$this->apiBaseUrl}/estado-sucursal/{$cod_sucursal}";
            $response = Http::put($url, [
                'estado' => $newState
            ]);

            if ($response->successful()) {
                $message = "Sucursal " . ($newState === 'ACTIVA' ? 'activada' : 'desactivada') . " exitosamente";
                return redirect()->route('sucursales.index')->with('success', $message);
            }

            return back()->withErrors(['error' => 'Error al cambiar el estado: ' . $response->body()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Excepción: ' . $e->getMessage()]);
        }
    }
}

