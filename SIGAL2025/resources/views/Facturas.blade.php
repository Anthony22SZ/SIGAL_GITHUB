@extends('layouts.app')

@section('title', 'Facturas de Compra - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3 dark:bg-gray-900 text-2xl font-semibold text-gray-900 dark:text-white">FACTURAS DE COMPRA</h1>
    
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar facturas...">
            </div>
        </div>
        <div>
            <a href="{{ route('FacturaCompra.crear') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Crear Factura</a>
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Número Factura</th>
                <th scope="col" class="px-6 py-3">Proveedor</th>
                <th scope="col" class="px-6 py-3">FECHA</th>
           
                <th scope="col" class="px-6 py-3">Total</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (is_array($facturas) && count($facturas) > 0)
            @foreach($facturas as $factura)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $factura['NUMERO_FACTURA'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $factura['NOMBRE_EMPRESA'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $factura['FECHA'] ?? 'N/A' }}</td>
                   
                    <td class="px-6 py-4">{{ $factura['TOTAL'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <button data-numero-factura="{{ $factura['NUMERO_FACTURA'] }}" class="ver-detalle font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver Detalles</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay facturas registradas.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="detalle-modal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center hidden" style="backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 w-full max-h-xl max-w-2xl">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detalles de Factura</h2>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="detalle-content" class="text-gray-900 dark:text-white text-sm">
            <!-- Los detalles se cargarán aquí -->
        </div>
    </div>
</div>

<script>
    // Búsqueda en la tabla
    document.getElementById('table-search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const columns = row.getElementsByTagName('td');
            let rowMatches = false;
            
            for (let i = 0; i < columns.length - 1; i++) {
                const cellText = columns[i].innerText.toLowerCase();
                if (cellText.includes(searchValue)) {
                    rowMatches = true;
                    break;
                }
            }
            
            row.style.display = rowMatches ? '' : 'none';
        });
    });

    // Mostrar detalles en el modal
    document.querySelectorAll('.ver-detalle').forEach(button => {
        button.addEventListener('click', function() {
            const numeroFactura = this.getAttribute('data-numero-factura');
            const modal = document.getElementById('detalle-modal');
            const content = document.getElementById('detalle-content');

            // Mostrar el modal
            modal.classList.remove('hidden');

            // Cargar detalles vía AJAX
            fetch(`{{ url('/facturas/detalle') }}/${numeroFactura}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.detalles.length > 0) {
                    const factura = data.detalles[0];
                    let html = `
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <p><strong>Nº Factura:</strong> ${factura.NUMERO_FACTURA}</p>
                            <p><strong>Proveedor:</strong> ${factura.NOMBRE_EMPRESA}</p>
                            <p><strong>Fecha:</strong> ${factura.FECHA}</p>
                            <p><strong>Impuesto:</strong> ${factura.IMPUESTO}</p>
                            <p><strong>Descuento:</strong> ${factura.DESCUENTO}</p>
                            <p><strong>Total:</strong> ${factura.TOTAL}</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="px-2 py-1">Código</th>
                                        <th class="px-2 py-1">Cant.</th>
                                        <th class="px-2 py-1">Precio</th>
                                        <th class="px-2 py-1">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    data.detalles.forEach(detalle => {
                        html += `
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-2 py-1">${detalle.MATERIAL_CODIGO || 'N/A'}</td>
                                <td class="px-2 py-1">${detalle.CANTIDAD || 'N/A'}</td>
                                <td class="px-2 py-1">${detalle.PRECIO || 'N/A'}</td>
                                <td class="px-2 py-1">${detalle.SUBTOTAL || 'N/A'}</td>
                            </tr>
                        `;
                    });
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    content.innerHTML = html;
                } else {
                    content.innerHTML = `<p class="text-red-500">${data.message || 'No se encontraron detalles'}</p>`;
                }
            })
            .catch(error => {
                content.innerHTML = `<p class="text-red-500">Error al cargar los detalles: ${error.message}</p>`;
            });
        });
    });

    // Cerrar el modal
    document.getElementById('close-modal').addEventListener('click', function() {
        document.getElementById('detalle-modal').classList.add('hidden');
    });
</script>
@endsection