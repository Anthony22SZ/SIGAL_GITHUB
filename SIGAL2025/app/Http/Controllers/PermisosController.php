<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class PermisosController extends Controller
{
    protected $client;

    public function __construct()
    {

        $this->client = new Client([
            'base_uri' => env('NODE_API_BASE_URL', 'http://localhost:3000'),
            'timeout' => 10.0,
        ]);
    }

    public function index(Request $request)
{
    try {
        // Obtener todos los roles
        $responseRoles = $this->client->get('/roles');
        $rolesData = json_decode($responseRoles->getBody()->getContents(), true);

        if (!isset($rolesData['success']) || !$rolesData['success']) {
            return back()->withErrors(['error' => 'Error al obtener los roles: ' . ($rolesData['message'] ?? 'Respuesta inesperada')]);
        }
        $roles = $rolesData['data'];

        // Obtener todas las pantallas (objetos)
        $responseObjetos = $this->client->get('/objetos');
        $objetosData = json_decode($responseObjetos->getBody()->getContents(), true);

        if (!isset($objetosData['success']) || !$objetosData['success']) {
            return back()->withErrors(['error' => 'Error al obtener las pantallas: ' . ($objetosData['message'] ?? 'Respuesta inesperada')]);
        }
        $pantallas = $objetosData['data'];

        // Obtener el rol seleccionado (de la solicitud o de la sesión)
        $codRol = $request->input('cod_rol', session('selected_rol', $roles[0]['COD_ROL'] ?? null));

        // Guardar el rol seleccionado en la sesión
        session(['selected_rol' => $codRol]);

        // Obtener permisos del rol seleccionado
        $permisos = [];
        if ($codRol) {
            $responsePermisos = $this->client->get("/permisos/{$codRol}");
            $permisosData = json_decode($responsePermisos->getBody()->getContents(), true);

            if (isset($permisosData['success']) && $permisosData['success']) {
                $permisos = $permisosData['data'];
            } else {
                Log::warning('No se encontraron permisos para el rol ' . $codRol . ': ' . json_encode($permisosData));
            }
        }

        $token = null; // No necesitamos el token por ahora

        return view('Permisos', compact('roles', 'pantallas', 'permisos', 'codRol', 'token'));
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
        Log::error('Error fetching permissions data: ' . $e->getMessage() . ' Response: ' . $responseBody);
        return back()->withErrors(['error' => 'Error al cargar los datos: ' . $responseBody]);
    } catch (\Exception $e) {
        Log::error('Unexpected error fetching permissions data: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Error al cargar los datos: ' . $e->getMessage()]);
    }
}
public function guardar(Request $request)
{
    $codRol = $request->input('cod_rol');
    $permisos = $request->input('permisos', []);
    $usuario = $request->attributes->get('usuario');
    $usuarioCrea = $usuario ? $usuario->nombre_usuario : 'Sistema';

    if (!$codRol) {
        return back()->withErrors(['error' => 'Debe seleccionar un rol']);
    }

    if (empty($permisos)) {
        return back()->withErrors(['error' => 'No se proporcionaron permisos para guardar']);
    }

    try {
        // Log para depurar los datos recibidos del formulario
        Log::debug('Datos recibidos del formulario: ' . json_encode($permisos));

        foreach ($permisos as $codObjeto => $permiso) {
            $data = [
                'cod_rol' => $codRol,
                'cod_objeto' => $codObjeto,
                'estado_modulo' => '1',
                'estado_seleccion' => $permiso['ver'] ?? '0', // Usar el valor real de "ver"
                'estado_insercion' => $permiso['crear'] ?? '0', // Usar el valor real de "crear"
                'estado_actualizacion' => $permiso['editar'] ?? '0', // Usar el valor real de "editar"
                'estado_eliminacion' => $permiso['eliminar'] ?? '0', // Usar el valor real de "eliminar"
                'usuario_crea' => $usuarioCrea,
            ];

            Log::debug('Enviando datos a /ACCESOS: ' . json_encode($data));

            $response = $this->client->post('/ACCESOS', [
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (!isset($responseData['success']) || !$responseData['success']) {
                Log::error('Error saving permission for object ' . $codObjeto . ': ' . json_encode($responseData));
                return back()->withErrors(['error' => 'Error al guardar el permiso para el objeto ' . $codObjeto . ': ' . ($responseData['message'] ?? 'Respuesta inesperada')]);
            }
        }

        // Limpiar el caché para que los permisos se actualicen
        Cache::forget("permissions_{$codRol}");

        // Mantener el rol seleccionado después de guardar
        return redirect()->route('permisos.index', ['cod_rol' => $codRol])->with('success', 'Permisos guardados exitosamente');
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $response = $e->getResponse();
        $responseBody = $response ? $response->getBody()->getContents() : 'No response body';
        Log::error('Error saving permissions: ' . $e->getMessage() . ' Response: ' . $responseBody);
        return back()->withErrors(['error' => 'Error al guardar los permisos: ' . $responseBody]);
    } catch (\Exception $e) {
        Log::error('Unexpected error saving permissions: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Error al guardar los permisos: ' . $e->getMessage()]);
    }
}
}