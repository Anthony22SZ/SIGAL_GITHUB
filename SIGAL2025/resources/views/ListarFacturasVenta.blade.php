@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Título y botón "Volver al Dashboard" -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Facturación</h1>
            <p class="text-gray-600">Emisión de facturas y documentos fiscales</p>
        </div>
        <a href="{{ route('dashboard') }}" 
           class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
            Volver al Dashboard
        </a>
    </div>

    <!-- Pestañas de navegación -->
    <div class="mb-6">
        <ul class="flex border-b border-gray-200">
            <li class="mr-1">
                <a href="{{ route('facturas.create') }}" 
                   class="inline-block px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-t-lg">
                    Emisión de Facturas
                </a>
            </li>
            <li class="mr-1">
                <a href="{{ route('facturas.index') }}" 
                   class="inline-block px-4 py-2 text-sm font-medium text-blue-600 bg-gray-50 rounded-t-lg">
                    Historial de Facturas
                </a>
            </li>
        </ul>
    </div>

    <!-- Sección "Historial de Facturas" -->
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Historial de Facturas</h2>
        <p class="text-gray-600 mb-6">Consulta y gestiona las facturas emitidas</p>

        <!-- Filtros -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mb-6">
            <div class="flex-1 mb-3 sm:mb-0">
                <input type="text" 
                       placeholder="Buscar por número o cliente" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex space-x-2">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Estado</option>
                    <option value="PAGADA">Pagada</option>
                    <option value="ANULADA">Anulada</option>
                    <option value="PENDIENTE">Pendiente</option>
                </select>
                <input type="date" 
                       class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" 
                       class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <!-- Tabla de facturas -->
        <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 border border-gray-200">
            <!-- Tabla para pantallas grandes -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Número</th>
                            <th scope="col" class="px-4 py-3">Fecha</th>
                            <th scope="col" class="px-4 py-3">Cliente</th>
                            <th scope="col" class="px-4 py-3">Método de Pago</th>
                            <th scope="col" class="px-4 py-3">Total</th>
                            <th scope="col" class="px-4 py-3">Estado</th>
                            <th scope="col" class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facturas as $factura)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3">{{ $factura['NUMERO_FACTURA'] }}</td>
                            <td class="px-4 py-3">{{ $factura['FECHA'] }}</td>
                            <td class="px-4 py-3">
                                {{ $factura['NOMBRE_CLIENTE'] }}<br>
                                <span class="text-gray-400 text-xs">RTN: {{ $factura['RTN_CLIENTE'] ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $factura['METODO_PAGO'] }}</td>
                            <td class="px-4 py-3">L. {{ number_format($factura['TOTAL'], 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $factura['ESTADO'] === 'PAGADA' ? 'bg-green-100 text-green-800' : 
                                       ($factura['ESTADO'] === 'ANULADA' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ $factura['ESTADO'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="{{ route('facturas.show', $factura['COD_FACTURA']) }}" 
                                   class="text-blue-600 hover:underline">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="#" 
                                   class="text-gray-600 hover:underline">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center">No hay facturas para mostrar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Lista para pantallas pequeñas -->
            <div class="block sm:hidden space-y-4">
                @forelse ($facturas as $factura)
                <div class="border border-gray-200 rounded-lg p-3">
                    <p class="text-sm text-gray-600"><span class="font-medium">Número:</span> {{ $factura['NUMERO_FACTURA'] }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Fecha:</span> {{ $factura['FECHA'] }}</p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Cliente:</span> {{ $factura['NOMBRE_CLIENTE'] }}<br>
                        <span class="text-gray-400 text-xs">RTN: {{ $factura['RTN_CLIENTE'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Método de Pago:</span> {{ $factura['METODO_PAGO'] }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Total:</span> L. {{ number_format($factura['TOTAL'], 2) }}</p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Estado:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $factura['ESTADO'] === 'PAGADA' ? 'bg-green-100 text-green-800' : 
                               ($factura['ESTADO'] === 'ANULADA' ? 'bg-red-100 text-red-800' : 
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ $factura['ESTADO'] }}
                        </span>
                    </p>
                    <div class="flex space-x-2 mt-2">
                        <a href="{{ route('facturas.show', $factura['COD_FACTURA']) }}" 
                           class="text-blue-600 hover:underline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="#" 
                           class="text-gray-600 hover:underline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-600 text-center">No hay facturas para mostrar</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection