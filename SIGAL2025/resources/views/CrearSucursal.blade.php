@extends('layouts.app')

@section('title', 'Crear Sucursal - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR SUCURSAL</h1>
      <form action="{{ route('sucursales.store') }}" method="POST">
          @csrf
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                  <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la Sucursal</label>
                  <input type="text" name="nombre" id="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre de la Sucursal" required maxlength="50">
              </div>
              <div class="w-full">
                  <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                  <input type="text" name="direccion" id="direccion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Dirección" required maxlength="100">
              </div>
              <div class="w-full">
    <label for="cod_empleado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        Empleado Encargado
    </label>
    <select name="cod_empleado" id="cod_empleado" 
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 
        focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
        dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
        
        <option value="" selected disabled>Seleccionar empleado</option>

        @if(!empty($empleados))
        @foreach($empleados as $empleado)
    <option value="{{ $empleado->COD_EMPLEADO }}">{{ $empleado->PRIMER_NOMBRE_E }} {{ $empleado->PRIMER_APELLIDO_E }}</option>
@endforeach
        @else
            <option value="" disabled>No hay empleados disponibles</option>
        @endif

    </select>
</div>
          </div>
          <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
              Crear Sucursal
          </button>
      </form>
  </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre (solo letras y espacios)
        document.getElementById('nombre').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });

        // Validación para la dirección (solo letras, números y espacios)
        document.getElementById('direccion').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9\s]/g, '');
        });

        // Validación para el código de empleado (solo números positivos)
        document.getElementById('cod_empleado').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endsection




