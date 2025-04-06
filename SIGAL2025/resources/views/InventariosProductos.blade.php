@extends('layouts.app')

@section('title', 'Inventario de Productos - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inventario de Productos</h1>
            <a href="{{ route('inventario.productos.crear') }}" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none dark:focus:ring-gray-800">Agregar Producto al Inventario</a>
        </div>

        <!-- Filtros -->
        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h2 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filtros</h2>
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input type="text" id="table-search" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar por código o nombre de producto">
                </div>
                <div class="w-48">
                    <select id="category-filter" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Categoría</option>
                        <!-- Aquí puedes llenar las categorías dinámicamente -->
                        <option value="MOCHILAS">Mochilas</option>
                        <option value="CARTERAS">Carteras</option>
                    </select>
                </div>
                <button id="apply-filters" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Aplicar Filtros</button>
            </div>
        </div>

        <!-- Selección de Sucursal -->
        <div class="mb-4 flex space-x-2" id="sucursal-buttons">
            <button data-sucursal="" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 {{ !$nombreSucursal ? 'bg-blue-600 text-white' : '' }}">Todas las Sucursales</button>
            @foreach ($sucursales as $sucursal)
                <button data-sucursal="{{ $sucursal['NOMBRE_SUCURSAL'] }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 {{ $nombreSucursal == $sucursal['NOMBRE_SUCURSAL'] ? 'bg-blue-600 text-white' : '' }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</button>
            @endforeach
        </div>

        <!-- Título de la Sucursal Seleccionada -->
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" id="sucursal-title">Inventario {{ $nombreSucursal ?: 'Todas las Sucursales' }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" id="sucursal-description">Productos disponibles en {{ $nombreSucursal ? 'la sucursal ' . $nombreSucursal : 'todas las sucursales' }}</p>

        <!-- Tabla de inventario -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Código</th>
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Descripción</th>
                        <th scope="col" class="px-6 py-3">Categoría</th>
                        <th scope="col" class="px-6 py-3">Stock Actual</th>
                        <th scope="col" class="px-6 py-3">Stock Mínimo</th>
                        <th scope="col" class="px-6 py-3">Precio Venta</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="inventario-table-body">
                    @foreach ($inventario as $productoInventario)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $productoInventario['CODIGO_PRODUCTO'] }}</td>
                            <td class="px-6 py-4">{{ $productoInventario['NOMBRE_PRODUCTO'] }}</td>
                            <td class="px-6 py-4">{{ $productoInventario['DESCRIPCION'] }}</td>
                            <td class="px-6 py-4">{{ $productoInventario['CATEGORIA'] }}</td>
                            <td class="px-6 py-4">{{ number_format($productoInventario['STOCK_ACTUAL'], 0) }}</td>
                            <td class="px-6 py-4">{{ number_format($productoInventario['STOCK_MINIMO'], 0) }}</td>
                            <td class="px-6 py-4">{{ number_format($productoInventario['PRECIO_VENTA'], 0) }}</td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="toggleMenu('menu-{{ $loop->index }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"></path>
                                        </svg>
                                    </button>
                                    <div id="menu-{{ $loop->index }}" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10 hidden dark:bg-gray-800 dark:border-gray-700">
                                        <a href="{{ route('inventario.productos.editar', [$productoInventario['CODIGO_PRODUCTO'], $productoInventario['COD_SUCURSAL']]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Editar</a>
                                        <button onclick="openModal('{{ $productoInventario['CODIGO_PRODUCTO'] }}', '{{ $productoInventario['COD_SUCURSAL'] }}', '{{ $productoInventario['NOMBRE_PRODUCTO'] }}', '{{ $productoInventario['NOMBRE_SUCURSAL'] }}', '{{ $productoInventario['STOCK_ACTUAL'] }}')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Agregar Stock</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal para agregar stock -->
        <div id="agregar-stock-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Registrar Entrada de Stock</h3>
                     <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="salida-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
                </div>
                <form id="agregar-stock-form" method="POST" action="{{ route('inventario.productos.agregar-stock') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="cod_producto" id="modal-cod-producto">
                    <input type="hidden" name="cod_sucursal" id="modal-cod-sucursal">
                    <div class="mb-4">
                        <label for="modal-codigo-producto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código Material</label>
                        <input type="text" id="modal-codigo-producto" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="modal-nombre-producto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Material</label>
                        <input type="text" id="modal-nombre-producto" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="modal-sucursal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sucursal</label>
                        <input type="text" id="modal-sucursal" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="modal-stock-actual" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad Actual</label>
                        <input type="text" id="modal-stock-actual" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cantidad</label>
                        <input type="number" step="0.01" min="0" name="cantidad" id="cantidad" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600">Registrar Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('table-search');
        const categoryFilter = document.getElementById('category-filter');
        const applyFiltersButton = document.getElementById('apply-filters');
        const sucursalButtons = document.querySelectorAll('#sucursal-buttons button');
        const sucursalTitle = document.getElementById('sucursal-title');
        const sucursalDescription = document.getElementById('sucursal-description');
        const tableBody = document.getElementById('inventario-table-body');

        // Filtrar por búsqueda y categoría
        applyFiltersButton.addEventListener('click', function () {
            const rows = Array.from(tableBody.getElementsByTagName('tr'));
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value.toLowerCase();

            rows.forEach(row => {
                const codigoProducto = row.cells[0].textContent.toLowerCase();
                const nombreProducto = row.cells[1].textContent.toLowerCase();
                const categoria = row.cells[3].textContent.toLowerCase();

                const matchesSearch = codigoProducto.includes(searchTerm) || nombreProducto.includes(searchTerm);
                const matchesCategory = selectedCategory === '' || categoria === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Cambiar sucursal
        sucursalButtons.forEach(button => {
            button.addEventListener('click', function () {
                const sucursal = this.getAttribute('data-sucursal');

                // Actualizar el estilo de los botones
                sucursalButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700', 'dark:bg-gray-600', 'dark:text-gray-300');
                });
                this.classList.remove('bg-gray-200', 'text-gray-700', 'dark:bg-gray-600', 'dark:text-gray-300');
                this.classList.add('bg-blue-600', 'text-white');

                // Actualizar el título y la descripción
                sucursalTitle.textContent = `Inventario ${sucursal || 'Todas las Sucursales'}`;
                sucursalDescription.textContent = `Productos disponibles en ${sucursal ? 'la sucursal ' + sucursal : 'todas las sucursales'}`;

                // Hacer una solicitud al backend para obtener los datos de la sucursal seleccionada
                console.log('Enviando solicitud para sucursal:', sucursal); // Depuración
                fetch(`/inventario?nombre_sucursal=${encodeURIComponent(sucursal)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Respuesta recibida:', response); // Depuración
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data); // Depuración
                        if (data.success && data.inventario) {
                            // Limpiar la tabla
                            tableBody.innerHTML = '';

                            // Llenar la tabla con los nuevos datos
                            data.inventario.forEach((producto, index) => {
                                const row = document.createElement('tr');
                                row.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600';
                                row.innerHTML = `
                                    <td class="px-6 py-4">${producto.CODIGO_PRODUCTO}</td>
                                    <td class="px-6 py-4">${producto.NOMBRE_PRODUCTO}</td>
                                    <td class="px-6 py-4">${producto.DESCRIPCION}</td>
                                    <td class="px-6 py-4">${producto.CATEGORIA}</td>
                                    <td class="px-6 py-4">${parseFloat(producto.STOCK_ACTUAL).toFixed(0)}</td>
                                    <td class="px-6 py-4">${parseFloat(producto.STOCK_MINIMO).toFixed(0)}</td>
                                    <td class="px-6 py-4">${parseFloat(producto.PRECIO_VENTA).toFixed(0)}</td>
                                    <td class="px-6 py-4">
                                        <div class="relative">
                                            <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="toggleMenu('menu-${index}')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"></path>
                                                </svg>
                                            </button>
                                            <div id="menu-${index}" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10 hidden dark:bg-gray-800 dark:border-gray-700">
                                                <a href="/inventario/productos/editar/${producto.CODIGO_PRODUCTO}/${producto.COD_SUCURSAL}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Editar</a>
                                                <button onclick="openModal('${producto.CODIGO_PRODUCTO}', '${producto.COD_SUCURSAL}', '${producto.NOMBRE_PRODUCTO}', '${producto.NOMBRE_SUCURSAL}', '${producto.STOCK_ACTUAL}')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Agregar Stock</button>
                                            </div>
                                        </div>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });
                        } else {
                            console.error('Error en los datos:', data.error || 'Datos no válidos');
                            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No se encontraron productos en el inventario</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar el inventario:', error);
                        tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Error al cargar el inventario: ' + error.message + '</td></tr>';
                    });
            });
        });
    });

    function openModal(codProducto, codSucursal, nombreProducto, sucursal, stockActual) {
        document.getElementById('modal-cod-producto').value = codProducto;
        document.getElementById('modal-cod-sucursal').value = codSucursal;
        document.getElementById('modal-codigo-producto').value = codProducto;
        document.getElementById('modal-nombre-producto').value = nombreProducto;
        document.getElementById('modal-sucursal').value = sucursal;
        document.getElementById('modal-stock-actual').value = parseFloat(stockActual).toFixed(0);
        document.getElementById('agregar-stock-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('agregar-stock-modal').classList.add('hidden');
        document.getElementById('cantidad').value = ''; // Limpiar el campo
    }

    function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    // Cerrar el menú si se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const menus = document.querySelectorAll('[id^="menu-"]');
        menus.forEach(menu => {
            if (!menu.classList.contains('hidden') && !menu.contains(event.target) && !event.target.closest('button[onclick*="toggleMenu"]')) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
@endsection