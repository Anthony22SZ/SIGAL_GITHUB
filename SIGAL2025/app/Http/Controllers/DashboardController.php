<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Hacer solicitud al endpoint de Node.js para estadísticas
            $response = Http::get('http://127.0.0.1:3000/estadisticas');

            if (!$response->successful()) {
                throw new Exception('Error al obtener estadísticas: ' . $response->status());
            }

            $data = $response->json();

            // Extraer las estadísticas del campo 'data'
            $estadisticas = $data['data'] ?? [];

            return view('dashboard', [
                'total_compras' => $estadisticas['total_compras'] ?? 0,
                'total_materiales' => $estadisticas['total_materiales'] ?? 0,
                'stock_bajo' => $estadisticas['stock_bajo'] ?? 0,
            ]);
        } catch (Exception $e) {
            \Log::error('Error al cargar dashboard: ' . $e->getMessage());
            return view('dashboard', [
                'total_compras' => 0,
                'total_materiales' => 0,
                'stock_bajo' => 0,
                'error' => 'Error al cargar estadísticas: ' . $e->getMessage()
            ]);
        }
    }
}