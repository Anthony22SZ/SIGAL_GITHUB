@extends('layouts.app')

@section('title', 'Actualizar Categoría - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">ACTUALIZAR CATEGORÍA</h1>
        <form id="actualizarCategoriaForm" action="{{ route('categorias.actualizar', $categoria['COD_CATEGORIA'] ?? '') }}" method="POST" class="space-y-6">
             @csrf
            @method('PUT')

            <!-- Campo oculto para COD_CATEGORIA -->
            <input type="hidden" name="COD_CATEGORIA" value="{{ $categoria['COD_CATEGORIA'] ?? '' }}">

            <!-- Sección de Campos -->
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="NOMBRE" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nombre</label>
                    <input type="text" id="NOMBRE" name="NOMBRE" value="{{ $categoria['NOMBRE'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre de la categoría" required maxlength="50" minlength="3">
                </div>
                <div class="w-full">
                    <label for="DESCRIPCION" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Descripción</label>
                    <input type="text" id="DESCRIPCION" name="DESCRIPCION" value="{{ $categoria['DESCRIPCION'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Descripción de la categoría" required maxlength="100" minlength="3">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('categorias.listar') }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</a>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Actualizar Categoría</button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre
        document.getElementById('NOMBRE').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de descripción
        document.getElementById('DESCRIPCION').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ0-9\s]/g, '');
        });

        document.getElementById("actualizarCategoriaForm").addEventListener("submit", function (event) {
            const NOMBRE = document.getElementById("NOMBRE").value;
            const DESCRIPCION = document.getElementById("DESCRIPCION").value;

            if (NOMBRE.length < 3 || NOMBRE.length > 50) {
                event.preventDefault();
                alert("El nombre debe tener entre 3 y 50 caracteres.");
                document.getElementById("NOMBRE").focus();
                return;
            }

            if (DESCRIPCION.length < 3 || DESCRIPCION.length > 100) {
                event.preventDefault();
                alert("La descripción debe tener entre 3 y 100 caracteres.");
                document.getElementById("DESCRIPCION").focus();
                return;
            }
        });
    });
    
</script>
@endsection