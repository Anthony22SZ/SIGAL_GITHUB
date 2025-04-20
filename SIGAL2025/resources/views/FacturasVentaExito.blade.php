@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Título y botón "Ver Factura" -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Factura Creada Exitosamente</h1>
        <a href="{{ route('facturas.show', $factura->cod_factura) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            Ver Factura
        </a>
    </div>

    <!-- Mensaje de éxito (usando el componente Alert de Flowbite) -->
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg" role="alert">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div>
                <span class="font-semibold block">¡Factura generada correctamente!</span>
                <p>
                    La factura ha sido creada con el número {{ $factura->numero_factura }} y número fiscal {{ $factura->numero_fiscal }}.
                    Puede ver la factura completa haciendo clic en el botón "Ver Factura" o continuar con otra operación.
                </p>
            </div>
        </div>
    </div>

    <!-- Resumen de la factura (usando el componente Card de Flowbite) -->
    <div class="mb-6 bg-white shadow-lg rounded-lg p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Resumen de Factura</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600"><span class="font-medium">Cliente:</span> {{ $factura->cliente }}</p>
                <p class="text-gray-600"><span class="font-medium">Método de Pago:</span> {{ $factura->metodo_pago }}</p>
            </div>
            <div>
                <p class="text-gray-600"><span class="font-medium">Total:</span> L. {{ number_format($factura->total, 2) }}</p>
                <p class="text-gray-600"><span class="font-medium">Fecha:</span> {{ $factura->fecha }}</p>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex justify-between items-center">
        <a href="{{ route('facturas.index') }}" 
           class="text-blue-600 hover:underline text-sm font-medium">
            ← Volver a la lista de facturas
        </a>
        <a href="{{ route('facturas.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            Crear nueva factura
        </a>
    </div>
</div>
@endsection