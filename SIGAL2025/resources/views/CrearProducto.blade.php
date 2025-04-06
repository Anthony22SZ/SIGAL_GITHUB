@extends('layouts.app')

@section('title', 'Crear Producto - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR PRODUCTO</h1>
        <form id="crearProductoForm" action="{{ route('productos.insertar') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="CODIGO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código</label>
                    <input type="text" name="CODIGO" id="CODIGO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código del producto" required maxlength="15" minlength="3">
                </div>
                <div class="w-full">
                    <label for="MODELO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modelo</label>
                    <input type="text" name="MODELO" id="MODELO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Modelo del producto" required maxlength="50" minlength="3">
                </div>
                <div class="sm:col-span-2">
                    <label for="DESCRIPCION" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                    <input type="text" name="DESCRIPCION" id="DESCRIPCION" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Descripción del producto" required maxlength="200" minlength="3">
                </div>
                <div class="w-full">
                    <label for="COD_CATEGORIA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
                    <select name="COD_CATEGORIA" id="COD_CATEGORIA" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar Categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria['COD_CATEGORIA'] }}">{{ $categoria['NOMBRE'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="TIEMPO_GARANTIA" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tiempo de Garantía (meses)</label>
                    <input type="number" name="TIEMPO_GARANTIA" id="TIEMPO_GARANTIA" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tiempo de garantía" required min="0" max="12" oninput="if(this.value.length > 2) this.value = this.value.slice(0, 2);">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
                Crear Producto
            </button>
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

        // Validación para el campo TIEMPO_GARANTIA
        document.getElementById('TIEMPO_GARANTIA').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Solo números
            if (this.value.length > 2) this.value = this.value.slice(0, 2); // Máximo 2 dígitos
            if (this.value > 12) this.value = 12; // Máximo valor 12
            if (this.value < 0) this.value = 0; // Mínimo valor 0
        });

        // Validación al enviar el formulario
        document.getElementById("crearProductoForm").addEventListener("submit", function (event) {
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

            if (DESCRIPCION.length < 3 || DESCRIPCION.length > 100) {
                event.preventDefault();
                alert("La descripción debe tener entre 3 y 200 caracteres.");
                document.getElementById("DESCRIPCION").focus();
                return;
            }

            if (!COD_CATEGORIA) {
                event.preventDefault();
                alert("Debes seleccionar una categoría.");
                document.getElementById("COD_CATEGORIA").focus();
                return;
            }

            if (TIEMPO_GARANTIA === '' || TIEMPO_GARANTIA < 0 || TIEMPO_GARANTIA > 12) {
                event.preventDefault();
                alert("El tiempo de garantía debe ser un número entre 0 y 12.");
                document.getElementById("TIEMPO_GARANTIA").focus();
                return;
            }
        });
    });
</script>
@endsection