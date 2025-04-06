<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckJwtToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->session()->get('token');
        if (!$token) {
            Log::warning('No token found in session');
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        try {
            $secret = env('JWT_SECRET', 'secreto_super_seguro');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            Log::debug('Decoded JWT: ' . json_encode((array) $decoded));
            $cod_rol = $decoded->cod_rol;
            $nombre_usuario = $decoded->nombre_usuario;

            $request->attributes->set('usuario', (object) [
                'cod_rol' => $cod_rol,
                'nombre_usuario' => $nombre_usuario,
                
            ]);

            // Compartir la información del usuario con todas las vistas
            view()->share('usuario', $request->attributes->get('usuario'));

            $routeName = $request->route()->getName();
            Log::debug('Route Name: ' . $routeName);
            $routeToObject = [
                'dashboard' => 'Dashboard',
                'usuarios.listar' => 'Usuarios',
                'usuarios.crear' => 'Usuarios',
                'usuarios.insertar' => 'Usuarios',
                'roles.listar' => 'Roles',
                'roles.crear' => 'Roles',
                'roles.insertar' => 'Roles',
                'accesos.listar' => 'Accesos',
                'accesos.crear' => 'Accesos',
                'accesos.insertar' => 'Accesos',
                'objetos.listar' => 'Pantalla',
                'objetos.crear' => 'Pantalla',
                'objetos.insertar' => 'Pantalla',
                'permisos.guardar'=> 'Permisos',
                'permisos.index' => 'Permisos',
                'clientes.listar'=> 'Clientes',
                'clientes.crear'=> 'Clientes',
                'clientes.insertar'=> 'Clientes',
                'clientes.editar'=> 'Clientes',
            ];

            $object = $routeToObject[$routeName] ?? null;
            Log::debug('Mapped Object: ' . ($object ?? 'None'));
            if (!$object) {
                Log::info('No object mapped for route, allowing access');
                return $next($request);
            }

            $permissions = Cache::remember("permissions_{$cod_rol}", 3600, function () use ($cod_rol) {
                $response = Http::get(env('NODE_API_BASE_URL', 'http://localhost:3000') . "/permisos/{$cod_rol}");
                $data = $response->json('data', []);
                Log::debug('Permissions fetched: ' . json_encode($data));
                return $data;
            });

            $hasAccess = collect($permissions)->contains(function ($permiso) use ($object) {
                return $permiso['NOMBRE_OBJETO'] === $object && $permiso['ESTADO_SELECCION'] === '1';
            });
            Log::debug('Has Access to ' . $object . ': ' . ($hasAccess ? 'Yes' : 'No'));

            if (!$hasAccess) {
                Log::warning('Access denied to ' . $routeName . ' for COD_ROL: ' . $cod_rol);
                return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta pantalla');
            }

            $request->attributes->add([
                'usuario' => $decoded,
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            Log::error('JWT Error: ' . $e->getMessage());
            $request->session()->flush();
            return redirect()->route('login')->with('error', 'Sesión inválida o expirada: ' . $e->getMessage());
        }

        return $next($request);
    }
}