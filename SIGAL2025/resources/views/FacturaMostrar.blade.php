@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    @if (isset($factura))
    <!-- Título y botones de acción -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-3 sm:space-y-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Factura</h1>
        <div class="flex space-x-2">
            <button onclick="printFactura()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2z"></path>
                </svg>
                Imprimir
            </button>
            <button onclick="downloadPDF()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                PDF
            </button>
            <a href="{{ route('facturas.index') }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                Cerrar
            </a>
        </div>
    </div>

    <!-- Factura -->
    <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 border border-gray-200">
        <!-- Encabezado de la empresa -->
        <div class="text-center mb-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">{{ $factura->empresa->RAZON_SOCIAL ?? 'N/A' }}</h2>
            <p class="text-gray-600 text-sm sm:text-base">RT: {{ $factura->empresa->RTN ?? 'N/A' }}</p>
            <p class="text-gray-600 text-sm sm:text-base">Tel: {{ $factura->empresa->TELEFONO ?? 'N/A' }} | {{ $factura->empresa->CIUDAD ?? 'N/A' }}, {{ $factura->empresa->DEPARTAMENTO ?? 'N/A' }}</p>
            <p class="text-gray-600 text-sm sm:text-base">Email: {{ $factura->empresa->EMAIL ?? 'N/A' }}</p>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mt-4">FACTURA</h1>
        </div>

        <!-- Datos de la factura y cliente -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Datos de la factura -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">DATOS DE FACTURA</h3>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Control Interno:</span> {{ $factura->factura->NUMERO_FACTURA ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Factura:</span> {{ $factura->factura->NUMERO_FISCAL ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Fecha:</span> {{ $factura->factura->FECHA ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Sucursal:</span> {{ $factura->factura->SUCURSAL ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Vendedor:</span> {{ $factura->factura->SUCURSAL ?? 'N/A' }}</p>
            </div>
            <!-- Datos del cliente -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">CLIENTE</h3>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Nombre:</span> {{ $factura->cliente->NOMBRE_CLIENTE ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">RTN:</span> {{ $factura->cliente->RTN_CLIENTE ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Dirección:</span> {{ $factura->factura->DIRECCION_SUCURSAL ?? 'N/A' }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Teléfono:</span> 9876-5432</p>
            </div>
        </div>

        <!-- Detalle de productos -->
        <div class="mb-6">
            <!-- Tabla para pantallas grandes -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Código</th>
                            <th scope="col" class="px-4 py-3">Descripción</th>
                            <th scope="col" class="px-4 py-3">Cant.</th>
                            <th scope="col" class="px-4 py-3">Precio Unit.</th>
                            <th scope="col" class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($factura->productos))
                            @foreach ($factura->productos as $producto)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-3">{{ $producto->CODIGO ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $producto->DESCRIPCION ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $producto->CANTIDAD ?? 'N/A' }}</td>
                                <td class="px-4 py-3">L. {{ number_format($producto->PRECIO_UNITARIO ?? 0, 2) }}</td>
                                <td class="px-4 py-3">L. {{ number_format($producto->SUBTOTAL ?? 0, 2) }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center">No hay productos para mostrar</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Lista para pantallas pequeñas -->
            <div class="block sm:hidden space-y-4">
                @if (!empty($factura->productos))
                    @foreach ($factura->productos as $producto)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <p class="text-sm text-gray-600"><span class="font-medium">Código:</span> {{ $producto->CODIGO ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Descripción:</span> {{ $producto->DESCRIPCION ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Cant.:</span> {{ $producto->CANTIDAD ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Precio Unit.:</span> L. {{ number_format($producto->PRECIO_UNITARIO ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Subtotal:</span> L. {{ number_format($producto->SUBTOTAL ?? 0, 2) }}</p>
                    </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-600 text-center">No hay productos para mostrar</p>
                @endif
            </div>
        </div>

        <!-- Forma de pago y totales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Forma de pago -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">FORMA DE PAGO</h3>
                <p class="text-gray-600 text-sm sm:text-base">{{ $factura->totales->FORMA_PAGO ?? 'N/A' }}</p>
            </div>
            <!-- Totales -->
            <div class="text-left sm:text-right">
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">TOTALES</h3>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Subtotal:</span> L. {{ number_format($factura->totales->SUBTOTAL ?? 0, 2) }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Descuento ({{ $factura->factura->PORCENTAJE_DESCUENTO ?? '0%' }}):</span> L. {{ number_format($factura->totales->MONTO_DESCUENTO ?? 0, 2) }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Subtotal con descuento:</span> L. {{ number_format($factura->totales->SUBTOTAL_NETO ?? 0, 2) }}</p>
                <p class="text-gray-600 text-sm sm:text-base"><span class="font-medium">Impuesto ({{ $factura->factura->PORCENTAJE_IMPUESTO ?? '0%' }}):</span> L. {{ number_format($factura->totales->MONTO_IMPUESTO ?? 0, 2) }}</p>
                <p class="text-gray-600 font-bold text-sm sm:text-base"><span class="font-medium">TOTAL:</span> L. {{ number_format($factura->totales->TOTAL ?? 0, 2) }}</p>
            </div>
        </div>

        <!-- Información fiscal -->
        <div class="mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">INFORMACIÓN FISCAL</h3>
            <p class="text-gray-600 text-sm sm:text-base">
                CAI: {{ $factura->cai->CODIGO_CAI ?? 'N/A' }}<br>
                Rango Autorizado: {{ $factura->cai->RANGO_INICIAL ?? 'N/A' }} - {{ $factura->cai->RANGO_FINAL ?? 'N/A' }}<br>
                Fecha Límite de Emisión: {{ $factura->cai->FECHA_VENCIMIENTO ?? 'N/A' }}<br>
                Modalidad: Autoimpresor
            </p>
        </div>

        <!-- Leyendas fiscales -->
        <div class="text-center text-gray-600 text-sm">
            <p>{{ $factura->leyendas->LEYENDA1 ?? 'N/A' }}</p>
            <p>{{ $factura->leyendas->LEYENDA2 ?? 'N/A' }}</p>
            <p>{{ $factura->leyendas->LEYENDA3 ?? 'N/A' }}</p>
        </div>
    </div>
    @else
    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg" role="alert">
        <p class="font-semibold">Error</p>
        <p>No se pudo cargar la factura. Por favor, intenta de nuevo.</p>
    </div>
    @endif
</div>

<script>
    // Función para imprimir la factura
    function printFactura() {
        const facturaContent = document.querySelector('.bg-white.shadow-lg.rounded-lg.p-4.sm\\:p-6.border.border-gray-200').innerHTML;
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Factura</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .text-center { text-align: center; }
                        .grid { display: grid; gap: 1.5rem; }
                        .sm\\:grid-cols-2 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
                        .sm\\:text-right { text-align: left; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 0.75rem; border-bottom: 1px solid #e5e7eb; }
                        th { background-color: #f9fafb; text-transform: uppercase; }
                        .font-bold { font-weight: 700; }
                        .font-medium { font-weight: 500; }
                        .text-sm { font-size: 0.875rem; }
                        .text-gray-600 { color: #4b5563; }
                        .text-gray-700 { color: #374151; }
                        .text-gray-800 { color: #1f2937; }
                        .hidden.sm\\:block { display: none; }
                        .block.sm\\:hidden { display: block; }
                    </style>
                </head>
                <body>
                    ${facturaContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }

    // Función para descargar PDF (placeholder)
    function downloadPDF() {
        alert('Funcionalidad de PDF no implementada. Puedes usar una librería como jsPDF para generar el PDF.');
        // Aquí podrías implementar la generación de PDF usando jsPDF o similar
    }
</script>
@endsection