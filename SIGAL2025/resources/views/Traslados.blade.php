@extends('layouts.app')

@section('title', 'Registrar Traslado - SIGAL')

@section('content')

<div id="traslados-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                    <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Nuevo Traslado</a>
                </li>
                <li class="me-2">
                    <a href="{{ route('traslados.historial') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Historial de Traslados</a>
                </li>
            </ul>
        </div>

        <!-- Formulario -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-md">
            <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">NUEVO TRASLADO</h2>
            <p class="mb-6 text-gray-500 dark:text-gray-400">Registra un nuevo traslado de productos entre sucursales</p>

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

            @if ($errors->any())
                <div class="mb-4 p-4 text-red-800 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-900">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="traslado-form" action="{{ route('inventario.traslados.realizar') }}" method="POST">
                @csrf
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="sucursal_origen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal de Origen</label>
                        <select id="sucursal_origen" name="nombre_sucursal_origen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="" disabled selected>Selecciona la sucursal de origen</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal['NOMBRE_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                            @endforeach
                        </select>
                        @error('nombre_sucursal_origen')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="sucursal_destino" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal de Destino</label>
                        <select id="sucursal_destino" name="nombre_sucursal_destino" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                            <option value="" disabled selected>Selecciona la sucursal de destino</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal['NOMBRE_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                            @endforeach
                        </select>
                        @error('nombre_sucursal_destino')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_traslado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha del Traslado</label>
                        <input type="date" id="fecha_traslado" name="fecha_traslado" value="{{ now()->format('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        @error('fecha_traslado')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="notas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notas</label>
                        <textarea id="notas" name="notas" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escribe notas adicionales (opcional)"></textarea>
                        @error('notas')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tabla de Productos -->
                <div class="mb-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Productos a Trasladar</h3>
                    <button type="button" id="agregar-producto-btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Agregar Producto
                    </button>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Código</th>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Cantidad</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productos-table-body">
                                <tr id="no-products-row">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No hay productos agregados.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @error('productos')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Registrar Traslado
                </button>
            </form>
        </div>
    </div>
</section>
</div>
<!-- Modal para Agregar Producto -->
<div id="producto-modal" tabindex="-1" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
    <div class="relative w-full max-w-md p-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Agregar Producto</h3>
                <button type="button" id="close-modal-btn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <form id="producto-form" class="p-4">
                <div class="mb-4">
                    <label for="codigo_producto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
                    <select id="codigo_producto" name="codigo_producto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option value="" disabled selected>Selecciona un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto['CODIGO'] }}" data-nombre="{{ $producto['MODELO'] }}">{{ $producto['CODIGO'] }} - {{ $producto['MODELO'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="cantidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ingresa la cantidad" required>
                </div>
                <button type="button" id="add-product-btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Agregar
                </button>
            </form>
        </div>
    </div>
</div>
<style>
    /* Fondo del modal con transparencia */
    #producto-modal {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #producto-modal:not(.hidden) ~ #traslados-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #traslados-container {
        transition: filter 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const agregarProductoBtn = document.getElementById('agregar-producto-btn');
        const productoModal = document.getElementById('producto-modal');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const addProductBtn = document.getElementById('add-product-btn');
        const productosTableBody = document.getElementById('productos-table-body');
        const noProductsRow = document.getElementById('no-products-row');
        const form = document.getElementById('traslado-form');

        let productos = [];

        // Mostrar el modal
        agregarProductoBtn.addEventListener('click', function () {
            productoModal.classList.remove('hidden');
        });

        // Cerrar el modal
        closeModalBtn.addEventListener('click', function () {
            productoModal.classList.add('hidden');
            document.getElementById('producto-form').reset();
        });

        // Agregar producto a la tabla
        addProductBtn.addEventListener('click', function () {
            const codigoProducto = document.getElementById('codigo_producto').value;
            const nombreProducto = document.getElementById('codigo_producto').selectedOptions[0].dataset.nombre;
            const cantidad = document.getElementById('cantidad').value;

            if (!codigoProducto || !cantidad || cantidad <= 0) {
                alert('Por favor, selecciona un producto y una cantidad válida.');
                return;
            }

            productos.push({ codigo_producto: codigoProducto, nombreProducto: nombreProducto, cantidad: cantidad });
            renderProductos();

            // Cerrar el modal y resetear el formulario
            productoModal.classList.add('hidden');
            document.getElementById('producto-form').reset();
        });

        // Renderizar productos en la tabla
        function renderProductos() {
            productosTableBody.innerHTML = '';
            if (productos.length === 0) {
                productosTableBody.appendChild(noProductsRow);
                return;
            }

            productos.forEach((producto, index) => {
                const row = document.createElement('tr');
                row.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-600');
                row.innerHTML = `
                    <td class="px-6 py-4">${producto.codigo_producto}</td>
                    <td class="px-6 py-4">${producto.nombreProducto}</td>
                    <td class="px-6 py-4">${producto.cantidad}</td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-red-600 hover:underline remove-product-btn" data-index="${index}">Eliminar</button>
                    </td>
                `;
                productosTableBody.appendChild(row);
            });

            // Añadir inputs ocultos al formulario para enviar los productos
            const hiddenInputsContainer = document.getElementById('hidden-inputs');
            if (hiddenInputsContainer) {
                hiddenInputsContainer.remove();
            }
            const hiddenInputs = document.createElement('div');
            hiddenInputs.id = 'hidden-inputs';
            hiddenInputs.innerHTML = productos.map((producto, index) => `
                <input type="hidden" name="productos[${index}][codigo_producto]" value="${producto.codigo_producto}">
                <input type="hidden" name="productos[${index}][cantidad]" value="${producto.cantidad}">
            `).join('');
            form.appendChild(hiddenInputs);

            // Añadir eventos a los botones de eliminar
            document.querySelectorAll('.remove-product-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const index = this.dataset.index;
                    productos.splice(index, 1);
                    renderProductos();
                });
            });
        }

        // Validar que haya productos antes de enviar el formulario
        form.addEventListener('submit', function (event) {
            if (productos.length === 0) {
                event.preventDefault();
                alert('Debes agregar al menos un producto para realizar el traslado.');
            }
        });
    });
</script>
@endsection