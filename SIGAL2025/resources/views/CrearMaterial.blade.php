
@extends('layouts.app')

@section('title', 'Crear material - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR MATERIALES</h1>
      <form action="{{ route('materiales.insertar') }}" method="POST">
          @csrf
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                  <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codigo del Material</label>
                  <input type="text" name="codigo" id="codigo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Codigo del Material" required  maxlength="8"pattern="[A-Za-z0-9]+" title="Solo se permiten letras y números" >
              </div>
              <div class="w-full">
                  <label for="material" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Material</label>
                  <input type="text" name="material" id="material" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre del Material" required maxlength="15" pattern="[A-Za-z0-9]+" title="Solo se permiten letras y números" >
              </div>
          </div>
          <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
              Crear material
          </button>
      </form>
  </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de código
        document.getElementById('codigo').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9]/g, '');
        });

        // Validación para el campo de material
        document.getElementById('material').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });
    });
</script>
@endsection