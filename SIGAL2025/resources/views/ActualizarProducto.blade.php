@extends('layouts.app')

@section('title', 'Actualizar Producto - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">ACTUALIZAR PRODUCTO</h1>
        <form id="actualizarProductoForm" action="{{ route('productos.actualizar', $producto['COD_PRODUCTO'] ?? '') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <!-- Campo oculto para COD_PRODUCTO -->
            <input type="hidden" name="COD_PRODUCTO" value="{{ $producto['COD_PRODUCTO'] ?? '' }}">

            <!-- Sección de Campos -->
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="CODIGO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Código</label>
                    <input type="text" id="CODIGO" name="CODIGO" value="{{ $producto['CODIGO'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código del producto" required maxlength="15" minlength="3">
                </div>
                <div class="w-full">
                    <label for="MODELO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Modelo</label>
                    <input type="text" id="MODELO" name="MODELO" value="{{ $producto['MODELO'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Modelo del producto" required maxlength="50" minlength="3">
                </div>
                <div class="sm:col-span-2">
                    <label for="DESCRIPCION" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Descripción</label>
                    <input type="text" id="DESCRIPCION" name="DESCRIPCION" value="{{ $producto['DESCRIPCION'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Descripción del producto" required maxlength="200" minlength="3">
                </div>
                <div class="w-full">
                    <label for="COD_CATEGORIA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Código Categoría</label>
                    <input type="number" id="COD_CATEGORIA" name="COD_CATEGORIA" value="{{ $producto['COD_CATEGORIA'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código de categoría" required min="1">
                </div>
                <div class="w-full">
                    <label for="TIEMPO_GARANTIA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tiempo Garantía (meses)</label>
                    <input type="number" id="TIEMPO_GARANTIA" name="TIEMPO_GARANTIA" value="{{ $producto['TIEMPO_GARANTIA'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tiempo de garantía" required min="0">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('productos.listar') }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Actualizar Producto</button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo CODIGO
        document.getElementById('CODIGO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9\-]/g, '');
        });

        // Validación para el campo MODELO
        document.getElementById('MODELO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '');
        });

        // Validación para el campo DESCRIPCION
        document.getElementById('DESCRIPCION').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '');
        });

        // Validación para el campo COD_CATEGORIA
        document.getElementById('COD_CATEGORIA').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value < 1) this.value = '';
        });

        // Validación para el campo TIEMPO_GARANTIA
        document.getElementById('TIEMPO_GARANTIA').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value < 0) this.value = '';
        });

        document.getElementById("actualizarProductoForm").addEventListener("submit", function (event) {
            const CODIGO = document.getElementById("CODIGO").value;
            const MODELO = document.getElementById("MODELO").value;
            const DESCRIPCION = document.getElementById("DESCRIPCION").value;
            const COD_CATEGORIA = document.getElementById("COD_CATEGORIA").value;
            const TIEMPO_GARANTIA = document.getElementById("TIEMPO_GARANTIA").value;

            if (CODIGO.length < 3 || CODIGO.length > 15) {
                event.preventDefault();
                alert("El código debe tener entre 3 y 15 caracteres.");
                document.getElementById("CODIGO").focus();
                return;
            }

            if (MODELO.length < 3 || MODELO.length > 50) {
                event.preventDefault();
                alert("El modelo debe tener entre 3 y 50 caracteres.");
                document.getElementById("MODELO").focus();
                return;
            }

            if (DESCRIPCION.length < 3 || DESCRIPCION.length > 200) {
                event.preventDefault();
                alert("La descripción debe tener entre 3 y 200 caracteres.");
                document.getElementById("DESCRIPCION").focus();
                return;
            }

            if (!COD_CATEGORIA || COD_CATEGORIA < 1) {
                event.preventDefault();
                alert("El código de categoría debe ser un número mayor a 0.");
                document.getElementById("COD_CATEGORIA").focus();
                return;
            }

            if (!TIEMPO_GARANTIA || TIEMPO_GARANTIA < 0) {
                event.preventDefault();
                alert("El tiempo de garantía debe ser un número mayor o igual a 0.");
                document.getElementById("TIEMPO_GARANTIA").focus();
                return;
            }
        });
    });
</script>
@endsection