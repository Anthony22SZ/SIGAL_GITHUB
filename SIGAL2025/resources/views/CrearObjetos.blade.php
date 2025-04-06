<!-- resources/views/objetos/crear.blade.php -->
@extends('layouts.app')

@section('title', 'Crear Objeto - SIGAL')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Crear Objeto</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('objetos.insertar') }}" class="space-y-6">
        @csrf

        <div>
            <label for="NOMBRE_OBJETO" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Objeto</label>
            <input type="text" name="NOMBRE_OBJETO" id="NOMBRE_OBJETO" value="{{ old('NOMBRE_OBJETO') }}" class="mt-1 block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            @error('NOMBRE_OBJETO')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="TIPO_OBJETO" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Objeto</label>
            <input type="text" name="TIPO_OBJETO" id="TIPO_OBJETO" value="{{ old('TIPO_OBJETO') }}" class="mt-1 block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            @error('TIPO_OBJETO')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="DESCRIPCION_OBJETO" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción del Objeto</label>
            <textarea name="DESCRIPCION_OBJETO" id="DESCRIPCION_OBJETO" class="mt-1 block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" rows="4">{{ old('DESCRIPCION_OBJETO') }}</textarea>
            @error('DESCRIPCION_OBJETO')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('objetos.listar') }}" class="inline-block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">Cancelar</a>
            <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">Guardar</button>
        </div>
    </form>
</div>
@endsection