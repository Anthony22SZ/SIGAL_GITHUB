@extends('layouts.app')

@section('title', 'Actualizar Producto en Inventario - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">ACTUALIZAR PRODUCTO EN INVENTARIO</h1>

        <form id="actualizarInventarioForm" action="{{ route('inventario.productos.actualizar') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Código del Producto (solo lectura) -->
                <div class="w-full">
                    <label for="CODIGO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Producto</label>
                    <input type="text" name="CODIGO" id="CODIGO" value="{{ $inventario['CODIGO_PRODUCTO'] }}" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                </div>

                <!-- Sucursal (mostrar NOMBRE_SUCURSAL, mantener COD_SUCURSAL oculto) -->
                <div class="w-full">
                    <label for="NOMBRE_SUCURSAL" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
                    <input type="text" id="NOMBRE_SUCURSAL" value="{{ $inventario['NOMBRE_SUCURSAL'] }}" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
                    <input type="hidden" name="COD_SUCURSAL" id="COD_SUCURSAL" value="{{ $inventario['COD_SUCURSAL'] }}">
                </div>

                <!-- Stock Mínimo -->
                <div class="w-full">
                    <label for="STOCK_MINIMO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Mínimo</label>
                    <input type="number" name="STOCK_MINIMO" id="STOCK_MINIMO" value="{{ $inventario['STOCK_MINIMO'] }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Stock Mínimo" required min="0" max="999999.99" step="0.01">
                    @error('STOCK_MINIMO')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio de Venta -->
                <div class="w-full">
                    <label for="PRECIO_VENTA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio de Venta</label>
                    <input type="number" name="PRECIO_VENTA" id="PRECIO_VENTA" value="{{ $inventario['PRECIO_VENTA'] }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Precio de Venta" required min="0" max="999999.99" step="0.01">
                    @error('PRECIO_VENTA')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
                Actualizar Inventario
            </button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('actualizarInventarioForm');

        form.addEventListener('submit', function (event) {
            const STOCK_MINIMO = document.getElementById('STOCK_MINIMO').value;
            const PRECIO_VENTA = document.getElementById('PRECIO_VENTA').value;

            console.log('Intentando enviar formulario con:', {
                CODIGO: document.getElementById('CODIGO').value,
                COD_SUCURSAL: document.getElementById('COD_SUCURSAL').value,
                STOCK_MINIMO,
                PRECIO_VENTA
            });

            // Validaciones mínimas
            if (!STOCK_MINIMO || isNaN(STOCK_MINIMO) || STOCK_MINIMO < 0 || parseFloat(STOCK_MINIMO) > 999999.99) {
                event.preventDefault();
                alert('El stock mínimo debe ser un número entre 0 y 999,999.99.');
                document.getElementById('STOCK_MINIMO').focus();
                return;
            }
            if (!PRECIO_VENTA || isNaN(PRECIO_VENTA) || PRECIO_VENTA < 0 || parseFloat(PRECIO_VENTA) > 999999.99) {
                event.preventDefault();
                alert('El precio de venta debe ser un número entre 0 y 999,999.99.');
                document.getElementById('PRECIO_VENTA').focus();
                return;
            }
        });
    });
</script>
@endsection



