
@extends('layouts.app')

@section('title', 'Crear Usuario - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Crear Usuario</h1>
      <form action="{{ route('usuarios.insertar') }}" method="POST">
          @csrf
          <input type="hidden" name="cod_empleado" value="{{ $cod_empleado }}">
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Empleado</label>
                  <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre del usuario" value="{{ $nombre }}" required>
              </div>
              <div class="w-full">
                  <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                  <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Correo electrónico" value="{{ $correo }}" required>
              </div>
              <div class="w-full">
                  <label for="cod_rol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rol</label>
                  <select name="cod_rol" id="cod_rol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                      <option value="" selected disabled>Seleccionar rol</option>
                      @foreach($roles as $rol)
                          <option value="{{ $rol['COD_ROL'] }}">{{ $rol['NOMBRE_ROL'] }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="w-full">
                  <label for="usuario_crea" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario Crea</label>
                  <input type="text" name="usuario_crea" id="usuario_crea" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Usuario que crea" required>
              </div>
          </div>
          <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
              Crear Usuario
          </button>
      </form>
  </div>
</section>
@endsection