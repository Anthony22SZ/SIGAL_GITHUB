@extends('layouts.app')

@section('title', 'Historial de Traslados - SIGAL')

@section('content')
<div id="historial-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
 
    <section class="bg-white dark:bg-gray-900">
        <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">TRASLADOS DE PRODUCTOS</h1>
                    <p class="text-gray-500 dark:text-gray-400">Gestiona el movimiento de productos entre sucursales</p>
                </div>
                <a href="{{ route('inventario.listar') }}" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Volver al Inventario
                </a>
            </div>

            <!-- Tabs -->
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <li class="me-2">
                        <a href="{{ route('traslados.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Nuevo Traslado</a>
                    </li>
                    <li class="me-2">
                        <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Historial de Traslados</a>
                    </li>
                </ul>
            </div>

            <!-- Tabla de Historial -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">HISTORIAL DE TRASLADOS</h2>
                <p class="mb-6 text-gray-500 dark:text-gray-400">Visualiza los traslados realizados entre sucursales</p>

                @if (session('success'))
                    <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 text-red-800 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-900">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filtros en una sola línea -->
                <div class="flex flex-wrap items-end gap-3 mb-6">
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Buscar por ID o descripción</label>
                        <input type="text" id="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar por ID o descripción">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label for="origen" class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Origen</label>
                        <select id="origen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal['NOMBRE_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label for="destino" class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Destino</label>
                        <select id="destino" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Todos</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal['NOMBRE_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[120px]">
                        <label for="fecha_desde" class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Desde</label>
                        <input type="date" id="fecha_desde" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex-1 min-w-[120px]">
                        <label for="fecha_hasta" class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Hasta</label>
                        <input type="date" id="fecha_hasta" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <button id="limpiar-filtros" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            Limpiar filtros
                        </button>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="traslados-table">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Fecha</th>
                                <th scope="col" class="px-6 py-3">Origen</th>
                                <th scope="col" class="px-6 py-3">Destino</th>
                                <th scope="col" class="px-6 py-3">Productos</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="traslados-table-body">
                            @forelse ($traslados as $traslado)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-traslado='@json($traslado)'>
                                    <td class="px-6 py-4">TR-{{ str_pad($traslado['COD_TRASLADO'], 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($traslado['FECHA_TRASLADO'])->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $traslado['SUCURSAL_ORIGEN'] }}</td>
                                    <td class="px-6 py-4">{{ $traslado['SUCURSAL_DESTINO'] }}</td>
                                    <td class="px-6 py-4">1 productos</td> <!-- Ajustar si hay más productos por traslado -->
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-200 dark:text-green-900">
                                            {{ $traslado['ESTADO'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button" class="flex items-center hover:underline view-details-btn">
                                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay traslados registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Modal de Detalles -->
<div id="detalle-modal" tabindex="-1" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
    <div class="relative w-full max-w-md p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalles del Traslado <span id="modal-traslado-id"></span></h3>
                <button type="button" id="close-modal-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sucursal de Origen</p>
                        <p id="modal-sucursal-origen" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sucursal de Destino</p>
                        <p id="modal-sucursal-destino" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Fecha del Traslado</p>
                        <p id="modal-fecha-traslado" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Estado</p>
                        <p id="modal-estado" class="text-sm text-green-800 dark:text-green-400"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Usuario</p>
                        <p id="modal-usuario" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Empleado</p>
                        <p id="modal-empleado" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Observaciones</p>
                    <p id="modal-notas" class="text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Productos</p>
                    <div class="mt-2">
                        <div class="flex justify-between">
                            <p id="modal-producto" class="text-sm text-gray-500 dark:text-gray-400"></p>
                            <p id="modal-cantidad" class="text-sm text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    /* Fondo del modal con transparencia */
    #detalle-modal {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #detalle-modal:not(.hidden) ~ #historial-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #historial-container {
        transition: filter 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trasladosTableBody = document.getElementById('traslados-table-body');
        const detalleModal = document.getElementById('detalle-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const searchInput = document.getElementById('search');
        const origenSelect = document.getElementById('origen');
        const destinoSelect = document.getElementById('destino');
        const fechaDesdeInput = document.getElementById('fecha_desde');
        const fechaHastaInput = document.getElementById('fecha_hasta');
        const limpiarFiltrosBtn = document.getElementById('limpiar-filtros');

        // Datos originales de los traslados
        const trasladosOriginales = @json($traslados);

        // Mostrar modal con detalles
        document.querySelectorAll('.view-details-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                const traslado = JSON.parse(row.dataset.traslado);

                document.getElementById('modal-traslado-id').textContent = `TR-${String(traslado.COD_TRASLADO).padStart(3, '0')}`;
                document.getElementById('modal-sucursal-origen').textContent = traslado.SUCURSAL_ORIGEN;
                document.getElementById('modal-sucursal-destino').textContent = traslado.SUCURSAL_DESTINO;
                document.getElementById('modal-fecha-traslado').textContent = new Date(traslado.FECHA_TRASLADO).toLocaleDateString('es-ES');
                document.getElementById('modal-estado').textContent = traslado.ESTADO;
                document.getElementById('modal-usuario').textContent = traslado.USUARIO;
                document.getElementById('modal-empleado').textContent = traslado.EMPLEADO;
                document.getElementById('modal-notas').textContent = traslado.NOTAS || 'Sin observaciones';
                document.getElementById('modal-producto').textContent = `${traslado.NOMBRE_PRODUCTO}`;
                document.getElementById('modal-cantidad').textContent = `${traslado.CANTIDAD} unidades`;

                detalleModal.classList.remove('hidden');
            });
        });

        // Cerrar modal
        closeModalBtn.addEventListener('click', function () {
            detalleModal.classList.add('hidden');
        });

        // Filtrar traslados
        function filtrarTraslados() {
            const search = searchInput.value.toLowerCase();
            const origen = origenSelect.value;
            const destino = destinoSelect.value;
            const fechaDesde = fechaDesdeInput.value ? new Date(fechaDesdeInput.value) : null;
            const fechaHasta = fechaHastaInput.value ? new Date(fechaHastaInput.value) : null;

            const trasladosFiltrados = trasladosOriginales.filter(traslado => {
                const id = `TR-${String(traslado.COD_TRASLADO).padStart(3, '0')}`.toLowerCase();
                const fechaTraslado = new Date(traslado.FECHA_TRASLADO);

                const coincideSearch = search === '' || id.includes(search) || traslado.NOMBRE_PRODUCTO.toLowerCase().includes(search);
                const coincideOrigen = origen === '' || traslado.SUCURSAL_ORIGEN === origen;
                const coincideDestino = destino === '' || traslado.SUCURSAL_DESTINO === destino;
                const coincideFechaDesde = !fechaDesde || fechaTraslado >= fechaDesde;
                const coincideFechaHasta = !fechaHasta || fechaTraslado <= fechaHasta;

                return coincideSearch && coincideOrigen && coincideDestino && coincideFechaDesde && coincideFechaHasta;
            });

            renderTraslados(trasladosFiltrados);
        }

        // Renderizar traslados
        function renderTraslados(traslados) {
            trasladosTableBody.innerHTML = '';
            if (traslados.length === 0) {
                trasladosTableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay traslados que coincidan con los filtros.</td></tr>';
                return;
            }

            traslados.forEach(traslado => {
                const row = document.createElement('tr');
                row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-600');
                row.dataset.traslado = JSON.stringify(traslado);
                row.innerHTML = `
                    <td class="px-6 py-4">TR-${String(traslado.COD_TRASLADO).padStart(3, '0')}</td>
                    <td class="px-6 py-4">${new Date(traslado.FECHA_TRASLADO).toLocaleDateString('es-ES')}</td>
                    <td class="px-6 py-4">${traslado.SUCURSAL_ORIGEN}</td>
                    <td class="px-6 py-4">${traslado.SUCURSAL_DESTINO}</td>
                    <td class="px-6 py-4">1 productos</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-200 dark:text-green-900">
                            ${traslado.ESTADO}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-blue-600 hover:underline view-details-btn">Ver detalles</button>
                    </td>
                `;
                trasladosTableBody.appendChild(row);
            });

            // Reasignar eventos a los botones de detalles
            document.querySelectorAll('.view-details-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const traslado = JSON.parse(row.dataset.traslado);

                    document.getElementById('modal-traslado-id').textContent = `TR-${String(traslado.COD_TRASLADO).padStart(3, '0')}`;
                    document.getElementById('modal-sucursal-origen').textContent = traslado.SUCURSAL_ORIGEN;
                    document.getElementById('modal-sucursal-destino').textContent = traslado.SUCURSAL_DESTINO;
                    document.getElementById('modal-fecha-traslado').textContent = new Date(traslado.FECHA_TRASLADO).toLocaleDateString('es-ES');
                    document.getElementById('modal-estado').textContent = traslado.ESTADO;
                    document.getElementById('modal-usuario').textContent = traslado.USUARIO;
                    document.getElementById('modal-empleado').textContent = traslado.EMPLEADO;
                    document.getElementById('modal-notas').textContent = traslado.NOTAS || 'Sin observaciones';
                    document.getElementById('modal-producto').textContent = `${traslado.NOMBRE_PRODUCTO}`;
                    document.getElementById('modal-cantidad').textContent = `${traslado.CANTIDAD} unidades`;

                    detalleModal.classList.remove('hidden');
                });
            });
        }

        // Eventos de los filtros
        searchInput.addEventListener('input', filtrarTraslados);
        origenSelect.addEventListener('change', filtrarTraslados);
        destinoSelect.addEventListener('change', filtrarTraslados);
        fechaDesdeInput.addEventListener('change', filtrarTraslados);
        fechaHastaInput.addEventListener('change', filtrarTraslados);

        // Limpiar filtros
        limpiarFiltrosBtn.addEventListener('click', function () {
            searchInput.value = '';
            origenSelect.value = '';
            destinoSelect.value = '';
            fechaDesdeInput.value = '';
            fechaHastaInput.value = '';
            renderTraslados(trasladosOriginales);
        });
    });
</script>
@endsection
