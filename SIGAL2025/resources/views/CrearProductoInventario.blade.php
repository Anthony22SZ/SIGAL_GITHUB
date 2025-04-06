@extends('layouts.app')

@section('title', 'Agregar Producto al Inventario - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">AGREGAR PRODUCTO AL INVENTARIO</h1>
                    <!-- Formulario -->
            <form id="crearProductoInventarioForm" action="{{ route('inventario.productos.insertar') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <div class="w-full">
            <label for="COD_PRODUCTO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
            <select name="COD_PRODUCTO" id="COD_PRODUCTO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                <option value="" selected disabled>Seleccionar Producto</option>
                @foreach ($productos as $producto)
                    <option value="{{ $producto['COD_PRODUCTO'] }}">{{ $producto['CODIGO'] }} - {{ $producto['MODELO'] }}</option>
                @endforeach
            </select>
        </div>
                <!-- Sucursal -->
                <div class="w-full">
                    <label for="COD_SUCURSAL" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
                    <select name="COD_SUCURSAL" id="COD_SUCURSAL" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar Sucursal</option>
                        @foreach ($sucursales ?? [] as $sucursal)
                        <option value="{{ $sucursal['COD_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                    @endforeach
                    </select>
                    @error('COD_SUCURSAL')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Cantidad -->
                <div class="w-full">
                    <label for="CANTIDAD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
                    <input type="number" name="CANTIDAD" id="CANTIDAD" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Cantidad" required min="0" max="10000" step="0.01" value="{{ old('CANTIDAD') }}">
                    @error('CANTIDAD')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock Mínimo -->
                <div class="w-full">
                    <label for="STOCK_MINIMO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock Mínimo</label>
                    <input type="number" name="STOCK_MINIMO" id="STOCK_MINIMO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Stock Mínimo" required min="0" max="10000" step="0.01" value="{{ old('STOCK_MINIMO') }}">
                    @error('STOCK_MINIMO')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio de Venta -->
                <div class="w-full">
                    <label for="PRECIO_VENTA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio de Venta</label>
                    <input type="number" name="PRECIO_VENTA" id="PRECIO_VENTA" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Precio de Venta" required min="0" max="10000" step="0.01" value="{{ old('PRECIO_VENTA') }}">
                    @error('PRECIO_VENTA')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            
        </div>
            <!-- Botón de envío -->
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
                Agregar al Inventario
            </button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('crearProductoInventarioForm');

        // Validación en tiempo real para campos numéricos
        const numericInputs = ['CANTIDAD', 'STOCK_MINIMO', 'PRECIO_VENTA'];
        numericInputs.forEach(id => {
            const input = document.getElementById(id);
            input.addEventListener('input', function () {
                // Permitir solo números y un punto decimal
                this.value = this.value.replace(/[^0-9.]/g, '');
                // Limitar a un solo punto decimal
                const parts = this.value.split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
                // Asegurar que no sea negativo
                if (this.value < 0) this.value = '';
                // Limitar el valor máximo
                if (parseFloat(this.value) > 10000) this.value = '10000';
            });
        });

        // Validación al enviar el formulario
        form.addEventListener('submit', function (event) {
            const COD_PRODUCTO = document.getElementById('COD_PRODUCTO').value;
            const COD_SUCURSAL = document.getElementById('COD_SUCURSAL').value;
            const CANTIDAD = document.getElementById('CANTIDAD').value;
            const STOCK_MINIMO = document.getElementById('STOCK_MINIMO').value;
            const PRECIO_VENTA = document.getElementById('PRECIO_VENTA').value;

            // Validar COD_PRODUCTO
            if (!COD_PRODUCTO || isNaN(COD_PRODUCTO) || COD_PRODUCTO <= 0) {
                event.preventDefault();
                alert('Debes seleccionar un producto válido.');
                document.getElementById('COD_PRODUCTO').focus();
                return;
            }

            // Validar COD_SUCURSAL
            if (!COD_SUCURSAL || isNaN(COD_SUCURSAL) || COD_SUCURSAL <= 0) {
                event.preventDefault();
                alert('Debes seleccionar una sucursal válida.');
                document.getElementById('COD_SUCURSAL').focus();
                return;
            }

            // Validar CANTIDAD
            if (!CANTIDAD || isNaN(CANTIDAD) || CANTIDAD < 0) {
                event.preventDefault();
                alert('La cantidad debe ser un número mayor o igual a 0.');
                document.getElementById('CANTIDAD').focus();
                return;
            }
            if (parseFloat(CANTIDAD) > 10000) {
                event.preventDefault();
                alert('La cantidad no puede superar 10000.');
                document.getElementById('CANTIDAD').focus();
                return;
            }

            // Validar STOCK_MINIMO
            if (!STOCK_MINIMO || isNaN(STOCK_MINIMO) || STOCK_MINIMO < 0) {
                event.preventDefault();
                alert('El stock mínimo debe ser un número mayor o igual a 0.');
                document.getElementById('STOCK_MINIMO').focus();
                return;
            }
            if (parseFloat(STOCK_MINIMO) > 10000) {
                event.preventDefault();
                alert('El stock mínimo no puede superar 10000.');
                document.getElementById('STOCK_MINIMO').focus();
                return;
            }

            // Validar PRECIO_VENTA
            if (!PRECIO_VENTA || isNaN(PRECIO_VENTA) || PRECIO_VENTA < 0) {
                event.preventDefault();
                alert('El precio de venta debe ser un número mayor o igual a 0.');
                document.getElementById('PRECIO_VENTA').focus();
                return;
            }
            if (parseFloat(PRECIO_VENTA) > 10000) {
                event.preventDefault();
                alert('El precio de venta no puede superar 100000.');
                document.getElementById('PRECIO_VENTA').focus();
                return;
            }
        });
    });
</script>
@endsection


