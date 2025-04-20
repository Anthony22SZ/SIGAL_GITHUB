@extends('layouts.app')

@section('title', 'Crear Factura')

@section('content')

<div id="facturaventa-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
<div class="py-4">
    <div class="mx-auto max-w-7xl">
        <h1 class="text-2xl font-semibold text-gray-900 mb-4">Crear Factura</h1>

        @if(session('error'))
            <div id="alert-error" class="flex p-4 mb-4 text-red-800 border-t-4 border-red-300 bg-red-50" role="alert">
                <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8" data-dismiss-target="#alert-error" aria-label="Close">
                    <span class="sr-only">Cerrar</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        @endif

        <form id="facturaForm" action="{{ route('facturas.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="productos" id="productos_json" value="[]">
            <input type="hidden" name="cod_empleado" value="{{ auth()->user()->cod_empleado ?? 1 }}">

            <!-- Información de Factura -->
            <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Información de Factura</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="cod_punto_emision" class="block mb-2 text-sm font-medium text-gray-900">Punto de Emisión</label>
                        <select id="cod_punto_emision" name="cod_punto_emision" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" selected disabled>Seleccionar punto de emisión</option>
                            @foreach($puntosEmision as $punto)
                                <option value="{{ $punto['COD_PUNTO_EMISION'] }}">{{ $punto['NOMBRE'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="cod_tipo_documento" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Documento</label>
                        <select id="cod_tipo_documento" name="cod_tipo_documento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" selected disabled>Seleccionar tipo de documento</option>
                            @foreach($tiposDocumento as $tipo)
                                <option value="{{ $tipo['COD_TIPO_DOCUMENTO'] }}">{{ $tipo['NOMBRE'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="cod_sucursal" class="block mb-2 text-sm font-medium text-gray-900">Sucursal</label>
                        <select id="cod_sucursal" name="cod_sucursal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" selected disabled>Seleccionar sucursal</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal['COD_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Cliente -->
            <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Cliente</h2>
                    <div class="flex space-x-2">
                        <button type="button" id="btnBuscarCliente" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Buscar Cliente
                        </button>
                        <button type="button" id="btnConsumidorFinal" class="py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                            Consumidor Final
                        </button>
                    </div>
                </div>

                <div id="clienteSeleccionado" class="hidden border p-3 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p id="nombreCliente" class="font-medium"></p>
                            <p id="rtnCliente" class="text-sm text-gray-600"></p>
                        </div>
                        <button type="button" id="btnLimpiarCliente" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <input type="hidden" id="cod_cliente" name="cod_cliente" value="">
                </div>

                <div id="sinClienteSeleccionado" class="text-center py-4 text-gray-500">
                    No hay cliente seleccionado. Busque un cliente o use "Consumidor Final".
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Productos</h2>
                    <button type="button" id="btnAgregarProducto" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Agregar Producto
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table id="tablaProductos" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Código</th>
                                <th scope="col" class="px-4 py-3">Descripción</th>
                                <th scope="col" class="px-4 py-3 text-center">Cantidad</th>
                                <th scope="col" class="px-4 py-3 text-right">Precio</th>
                                <th scope="col" class="px-4 py-3 text-right">Subtotal</th>
                                <th scope="col" class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="productosBody">
                            <tr id="sinProductos">
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    No hay productos agregados. Haga clic en "Agregar Producto" para comenzar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totales -->
            <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Totales</h2>
                    <button type="button" id="btnToggleTotales" class="text-gray-500 hover:text-gray-700">
                        <svg id="iconExpandir" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        <svg id="iconContraer" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </button>
                </div>

                <div id="seccionTotales" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-700">Subtotal:</span>
                                <span id="subtotal" class="font-medium">L. 0.00</span>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-gray-700">Descuento (%):</span>
                                <div class="flex-1">
                                    <input type="number" id="descuento" name="descuento" min="0" max="100" step="1" value="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <span id="montoDescuento" class="font-medium">L. 0.00</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-700">Subtotal con descuento:</span>
                                <span id="subtotalConDescuento" class="font-medium">L. 0.00</span>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-gray-700">Impuesto (%):</span>
                                <div class="flex-1">
                                    <input type="number" id="impuesto" name="impuesto" min="0" max="100" step="1" value="15" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <span id="montoImpuesto" class="font-medium">L. 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="text-lg font-bold text-gray-900">TOTAL:</span>
                    <span id="total" class="text-lg font-bold text-gray-900">L. 0.00</span>
                </div>
            </div>

            <!-- Método de Pago -->
            <div class="bg-white shadow-md rounded-lg p-4 mb-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Método de Pago</h2>
                <div>
                    <label for="metodo_pago" class="block mb-2 text-sm font-medium text-gray-900">Seleccione el método de pago</label>
                    <select id="metodo_pago" name="metodo_pago" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TARJETA">Tarjeta de Crédito/Débito</option>
                        <option value="TRANSFERENCIA">Transferencia Bancaria</option>
                    </select>
                </div>
            </div>

            <!-- Botón Generar Factura -->
            <div class="flex justify-end">
                <button type="submit" id="btnGenerarFactura" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base px-6 py-3 focus:outline-none">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Generar Factura
                </button>
            </div>
        </form>
    </div>
</div>
</div>
<!-- Modal Buscar Cliente -->
<div id="modalBuscarCliente" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Buscar Cliente</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center cerrar-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Cerrar</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="busquedaCliente" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Buscar por nombre o RTN ">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Nombre</th>
                                <th scope="col" class="px-4 py-3">RTN</th>
                                <th scope="col" class="px-4 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="clientesBody">
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500">Cargando clientes...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex items-center justify-between p-6 space-x-2 border-t border-gray-200 rounded-b">
                <button type="button" id="btnConsumidorFinalModal" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Consumidor Final</button>
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 cerrar-modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Producto -->
<div id="modalBuscarProducto" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
<div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Buscar Producto</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center cerrar-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Cerrar</span>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="busquedaProducto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Buscar por código, nombre o descripción">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Código</th>
                                <th scope="col" class="px-4 py-3">Descripción</th>
                                <th scope="col" class="px-4 py-3 text-center">Stock</th>
                                <th scope="col" class="px-4 py-3 text-right">Precio</th>
                                <th scope="col" class="px-4 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="productosModalBody">
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Cargando productos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b">
                <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 cerrar-modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    /* Fondo del modal con transparencia */
    #modalBuscarCliente {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #modalBuscarCliente:not(.hidden) ~ #facturaventa-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #facturaventa-container {
        transition: filter 0.3s ease;
    }
</style>
<style>
    /* Fondo del modal con transparencia */
    #modalBuscarProducto {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #modalBuscarProducto:not(.hidden) ~ #facturaventa-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #facturaventa-container {
        transition: filter 0.3s ease;
    }
</style>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let clientes = [];
        let productos = [];
        let productosSeleccionados = [];

        const modalBuscarCliente = document.getElementById('modalBuscarCliente');
        const modalBuscarProducto = document.getElementById('modalBuscarProducto');
        const backdrop = document.createElement('div');
        backdrop.className = 'fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80';

        function setupBackdrop() {
            backdrop.addEventListener('click', function() {
                hideModal(modalBuscarCliente);
                hideModal(modalBuscarProducto);
            });
        }

        function showModal(modal) {
            document.body.appendChild(backdrop);
            modal.classList.remove('hidden');
            modal.removeAttribute('aria-hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideModal(modal) {
    if (document.body.contains(backdrop)) {
        document.body.removeChild(backdrop);
    }
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');

    // Solución: mover el foco a un elemento fuera del modal
    document.getElementById('btnBuscarCliente')?.focus();
}
        function setupModalCloseEvents() {
            document.querySelectorAll('.cerrar-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('[id^="modal"]');
                    hideModal(modal);
                });
            });
        }

        function cargarDatosFacturacion() {
    // Cargar clientes
    fetch('/api/clientes', {
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'include' // importante si usas sesiones de Laravel
})

    .then(response => {
        console.log('Estado de /api/clientes:', response.status, response.statusText);
        return response.text().then(text => {
            console.log('Respuesta completa de /api/clientes:', text.substring(0, 500));
            if (!response.ok) {
                throw new Error(`Error ${response.status}: No se pudieron cargar los clientes`);
            }
            return JSON.parse(text);
        });
    })
    .then(data => {
        console.log('Datos recibidos de /api/clientes:', data);
        clientes = Array.isArray(data) ? data : [data];
        clientes = clientes.map(cliente => ({
            cod_cliente: cliente.COD_CLIENTE,
            nombre: cliente.NOMBRE_COMPLETO,
            rtn: cliente.RTN,
        }));
        renderizarClientes(clientes);
    })
    .catch(error => {
        console.error('Error al cargar clientes:', error);
    });

    // Cargar productos
    fetch('/inventario-productos', {
        headers: { 'Authorization': 'Bearer ' + (sessionStorage.getItem('api_token') || '') }
    })
        .then(response => {
            if (!response.ok) throw new Error(`Error ${response.status}: No se pudieron cargar los productos`);
            return response.json();
        })
        .then(result => {
            if (result.success && result.data) {
                productos = result.data.map(item => ({
                    codigo_producto: item.CODIGO_PRODUCTO,
                    codigo: item.CODIGO_PRODUCTO,
                    nombre: item.NOMBRE_PRODUCTO,
                    descripcion: item.DESCRIPCION || '',
                    stock: parseFloat(item.STOCK_ACTUAL) || 0,
                    stock_minimo: parseFloat(item.STOCK_MINIMO) || 0,
                    precio_venta: parseFloat(item.PRECIO_VENTA) || 0,
                    cod_sucursal: item.COD_SUCURSAL
                }));
                filtrarProductosPorSucursal();
            } else {
                throw new Error('Formato de respuesta inválido');
            }
        })
        .catch(error => {
            console.error('Error al cargar productos:', error);
            document.getElementById('productosModalBody').innerHTML = `<tr><td colspan="5" class="px-4 py-6 text-center text-red-500">Error al cargar productos: ${error.message}</td></tr>`;
        });
}
        function renderizarClientes(clientesData) {
            const clientesBody = document.getElementById('clientesBody');
            clientesBody.innerHTML = clientesData.length === 0 ? 
                '<tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No se encontraron clientes</td></tr>' :
                clientesData.map(cliente => `
                    <tr>
                        <td class="px-4 py-3">${cliente.nombre}</td>
                        <td class="px-4 py-3">${cliente.rtn || 'N/A'}</td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" class="text-blue-600 hover:text-blue-900 select-cliente" data-cliente='${JSON.stringify(cliente)}'>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </td>
                    </tr>
                `).join('');

            document.querySelectorAll('.select-cliente').forEach(button => {
                button.addEventListener('click', function() {
                    const cliente = JSON.parse(this.getAttribute('data-cliente'));
                    seleccionarCliente(cliente);
                    hideModal(modalBuscarCliente);
                });
            });
        }

        function renderizarProductos(productosData) {
            const productosModalBody = document.getElementById('productosModalBody');
            productosModalBody.innerHTML = productosData.length === 0 ? 
                '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No se encontraron productos</td></tr>' :
                productosData.map(producto => {
                    const stockClass = producto.stock <= 0 ? 'text-red-600' : 
                                    producto.stock < producto.stock_minimo ? 'text-yellow-600' : 'text-green-600';
                    return `
                        <tr>
                            <td class="px-4 py-3">${producto.codigo}</td>
                            <td class="px-4 py-3">${producto.nombre} <div class="text-xs text-gray-500">${producto.descripcion}</div></td>
                            <td class="px-4 py-3 text-center"><span class="${stockClass}">${producto.stock}</span></td>
                            <td class="px-4 py-3 text-right">L. ${producto.precio_venta.toFixed(2)}</td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-900 select-producto ${producto.stock <= 0 ? 'opacity-50 cursor-not-allowed' : ''}" 
                                        data-producto='${JSON.stringify(producto)}' ${producto.stock <= 0 ? 'disabled' : ''}>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');

            document.querySelectorAll('.select-producto:not(:disabled)').forEach(button => {
                button.addEventListener('click', function() {
                    const producto = JSON.parse(this.getAttribute('data-producto'));
                    agregarProducto(producto);
                    hideModal(modalBuscarProducto);
                });
            });
        }

        function filtrarClientes() {
            const termino = document.getElementById('busquedaCliente').value.toLowerCase();
            const clientesFiltrados = termino ? 
                clientes.filter(c => 
                    (c.nombre && c.nombre.toLowerCase().includes(termino)) || 
                    (c.rtn && c.rtn.toLowerCase().includes(termino)) || 
                    (c.telefono && c.telefono.toLowerCase().includes(termino))
                ) : clientes;
            renderizarClientes(clientesFiltrados);
        }

        function filtrarProductosPorSucursal() {
    const sucursalSeleccionada = document.getElementById('cod_sucursal').value;
    let nombreSucursal = null;
    if (sucursalSeleccionada) {
        const sucursal = @json($sucursales).find(s => s.COD_SUCURSAL == sucursalSeleccionada);
        nombreSucursal = sucursal ? sucursal.NOMBRE_SUCURSAL : null;
    }
    
    fetch('/inventario-productos' + (nombreSucursal ? `?nombre_sucursal=${encodeURIComponent(nombreSucursal)}` : ''), {
        headers: { 'Authorization': 'Bearer ' + (sessionStorage.getItem('api_token') || '') }
    })
        .then(response => {
            if (!response.ok) throw new Error(`Error ${response.status}: No se pudieron cargar los productos`);
            return response.json();
        })
        .then(result => {
            if (result.success && result.data) {
                productos = result.data.map(item => ({
                    codigo_producto: item.CODIGO_PRODUCTO,
                    codigo: item.CODIGO_PRODUCTO,
                    nombre: item.NOMBRE_PRODUCTO,
                    descripcion: item.DESCRIPCION || '',
                    stock: parseFloat(item.STOCK_ACTUAL) || 0,
                    stock_minimo: parseFloat(item.STOCK_MINIMO) || 0,
                    precio_venta: parseFloat(item.PRECIO_VENTA) || 0,
                    cod_sucursal: item.COD_SUCURSAL
                }));
                filtrarProductos(productos);
            } else {
                throw new Error('Formato de respuesta inválido');
            }
        })
        .catch(error => {
            console.error('Error al filtrar productos:', error);
            document.getElementById('productosModalBody').innerHTML = `<tr><td colspan="5" class="px-4 py-6 text-center text-red-500">Error al cargar productos: ${error.message}</td></tr>`;
        });
}
        function filtrarProductos(productosData = productos) {
            const termino = document.getElementById('busquedaProducto').value.toLowerCase();
            const productosFiltrados = termino ? 
                productosData.filter(p => 
                    (p.codigo && p.codigo.toLowerCase().includes(termino)) || 
                    (p.nombre && p.nombre.toLowerCase().includes(termino)) || 
                    (p.descripcion && p.descripcion.toLowerCase().includes(termino))
                ) : productosData;
            renderizarProductos(productosFiltrados);
        }

        function seleccionarCliente(cliente) {
            const elements = {
                cod_cliente: document.getElementById('cod_cliente'),
                nombreCliente: document.getElementById('nombreCliente'),
                rtnCliente: document.getElementById('rtnCliente'),
               clienteSeleccionado: document.getElementById('clienteSeleccionado'),
                sinClienteSeleccionado: document.getElementById('sinClienteSeleccionado')
            };

            if (!elements.cod_cliente || !elements.nombreCliente || !elements.rtnCliente) {
                console.error('Elementos del DOM no encontrados para seleccionar cliente');
                return;
            }

            elements.cod_cliente.value = cliente.cod_cliente || '';
            elements.nombreCliente.textContent = cliente.nombre;
            elements.rtnCliente.textContent = `RTN: ${cliente.rtn || 'N/A'}`;
            
            

            elements.clienteSeleccionado.classList.remove('hidden');
            elements.sinClienteSeleccionado.classList.add('hidden');
        }

        function seleccionarConsumidorFinal() {
            seleccionarCliente({ cod_cliente: null, nombre: "CONSUMIDOR FINAL", rtn: "CF" });
        }

        function limpiarCliente() {
            const elements = {
                cod_cliente: document.getElementById('cod_cliente'),
                clienteSeleccionado: document.getElementById('clienteSeleccionado'),
                sinClienteSeleccionado: document.getElementById('sinClienteSeleccionado')
            };
            elements.cod_cliente.value = '';
            elements.clienteSeleccionado.classList.add('hidden');
            elements.sinClienteSeleccionado.classList.remove('hidden');
        }

        function agregarProducto(producto) {
            const existente = productosSeleccionados.find(p => p.codigo_producto === producto.codigo_producto);
            if (existente) {
                existente.cantidad += 1;
                existente.subtotal = existente.cantidad * existente.precio;
            } else {
                productosSeleccionados.push({
                    codigo_producto: producto.codigo_producto,
                    codigo: producto.codigo,
                    nombre: producto.nombre,
                    descripcion: producto.descripcion || '',
                    cantidad: 1,
                    precio: producto.precio_venta,
                    subtotal: producto.precio_venta
                });
            }
            actualizarTablaProductos();
            calcularTotales();
        }

        function actualizarTablaProductos() {
            const productosBody = document.getElementById('productosBody');
            productosBody.innerHTML = productosSeleccionados.length === 0 ? 
                '<tr id="sinProductos"><td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay productos agregados. Haga clic en "Agregar Producto" para comenzar.</td></tr>' :
                productosSeleccionados.map((p, index) => `
                    <tr>
                        <td class="px-4 py-3">${p.codigo}</td>
                        <td class="px-4 py-3">${p.nombre}<div class="text-xs text-gray-500">${p.descripcion}</div></td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" min="1" value="${p.cantidad}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-16 p-1 text-center cantidad-producto" data-index="${index}">
                        </td>
                        <td class="px-4 py-3 text-right">L. ${p.precio.toFixed(2)}</td>
                        <td class="px-4 py-3 text-right">L. ${p.subtotal.toFixed(2)}</td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" class="text-red-600 hover:text-red-900 eliminar-producto" data-index="${index}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                `).join('');

            document.querySelectorAll('.cantidad-producto').forEach(input => {
                input.addEventListener('change', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const cantidad = parseInt(this.value) || 1;
                    if (cantidad < 1) this.value = 1;
                    productosSeleccionados[index].cantidad = Math.max(1, cantidad);
                    productosSeleccionados[index].subtotal = productosSeleccionados[index].cantidad * productosSeleccionados[index].precio;
                    actualizarTablaProductos();
                    calcularTotales();
                });
            });

            document.querySelectorAll('.eliminar-producto').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    productosSeleccionados.splice(index, 1);
                    actualizarTablaProductos();
                    calcularTotales();
                });
            });

            document.getElementById('productos_json').value = JSON.stringify(productosSeleccionados);
        }

        function calcularTotales() {
            const subtotal = productosSeleccionados.reduce((sum, p) => sum + p.subtotal, 0);
            const descuentoPorcentaje = parseFloat(document.getElementById('descuento').value) || 0;
            const descuento = subtotal * (descuentoPorcentaje / 100);
            const subtotalConDescuento = subtotal - descuento;
            const impuestoPorcentaje = parseFloat(document.getElementById('impuesto').value) || 0;
            const impuesto = subtotalConDescuento * (impuestoPorcentaje / 100);
            const total = subtotalConDescuento + impuesto;

            document.getElementById('subtotal').textContent = `L. ${subtotal.toFixed(2)}`;
            document.getElementById('montoDescuento').textContent = `L. ${descuento.toFixed(2)}`;
            document.getElementById('subtotalConDescuento').textContent = `L. ${subtotalConDescuento.toFixed(2)}`;
            document.getElementById('montoImpuesto').textContent = `L. ${impuesto.toFixed(2)}`;
            document.getElementById('total').textContent = `L. ${total.toFixed(2)}`;
        }

        function toggleSeccionTotales() {
            const seccionTotales = document.getElementById('seccionTotales');
            const iconExpandir = document.getElementById('iconExpandir');
            const iconContraer = document.getElementById('iconContraer');
            seccionTotales.classList.toggle('hidden');
            iconExpandir.classList.toggle('hidden');
            iconContraer.classList.toggle('hidden');
        }

        function validarFormulario(event) {
            if (productosSeleccionados.length === 0) {
                event.preventDefault();
                alert('Debe agregar al menos un producto a la factura');
                return false;
            }
            if (!document.getElementById('cod_cliente').value && document.getElementById('cod_cliente').value !== null) {
                event.preventDefault();
                alert('Debe seleccionar un cliente o usar "Consumidor Final"');
                return false;
            }
            return true;
        }

        setupBackdrop();
        setupModalCloseEvents();
        cargarDatosFacturacion();

        document.getElementById('btnBuscarCliente').addEventListener('click', () => showModal(modalBuscarCliente));
        document.getElementById('btnAgregarProducto').addEventListener('click', () => showModal(modalBuscarProducto));
        document.getElementById('btnConsumidorFinal').addEventListener('click', seleccionarConsumidorFinal);
        document.getElementById('btnConsumidorFinalModal').addEventListener('click', () => {
            seleccionarConsumidorFinal();
            hideModal(modalBuscarCliente);
        });
        document.getElementById('btnLimpiarCliente').addEventListener('click', limpiarCliente);
        document.getElementById('busquedaCliente').addEventListener('input', filtrarClientes);
        document.getElementById('busquedaProducto').addEventListener('input', () => filtrarProductos());
        document.getElementById('cod_sucursal').addEventListener('change', filtrarProductosPorSucursal);
        document.getElementById('btnToggleTotales').addEventListener('click', toggleSeccionTotales);
        document.getElementById('descuento').addEventListener('input', calcularTotales);
        document.getElementById('impuesto').addEventListener('input', calcularTotales);
        document.getElementById('facturaForm').addEventListener('submit', validarFormulario);
    });
</script>
@endsection
