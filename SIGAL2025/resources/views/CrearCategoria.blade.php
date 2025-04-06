@extends('layouts.app')

@section('title', 'Crear Categoría - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR CATEGORÍA</h1>
        <form id="crearCategoriaForm" action="{{ route('categorias.insertar') }}" method="POST">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre de la categoría" required maxlength="50" minlength="3">
                </div>
                <div class="w-full">
                    <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                    <input type="text" name="descripcion" id="descripcion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Descripción de la categoría" required maxlength="100" minlength="3">
                </div>
            </div>
            <div class="flex justify-end gap-4">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
                Crear Categoría
            </button>
            </div>
        </form>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre
        document.getElementById('nombre').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de descripción
        document.getElementById('descripcion').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ0-9\s]/g, '');
        });

        // Validación del formulario al enviar
        document.getElementById("crearCategoriaForm").addEventListener("submit", function (event) {
            const nombre = document.getElementById("nombre").value;
            const descripcion = document.getElementById("descripcion").value;

            if (nombre.length < 3 || nombre.length > 50) {
                event.preventDefault();
                alert("El nombre debe tener entre 3 y 50 caracteres.");
                document.getElementById("nombre").focus();
                return;
            }

            if (descripcion.length < 3 || descripcion.length > 100) {
                event.preventDefault();
                alert("La descripción debe tener entre 3 y 100 caracteres.");
                document.getElementById("descripcion").focus();
                return;
            }
        });
    });
</script>
@endsection
