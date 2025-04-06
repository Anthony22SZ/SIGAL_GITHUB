@extends('layouts.app')

@section('title', 'Crear Rol - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3">CREAR ROL</h1>

    @if ($errors->any())
        <div class="px-6 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="px-6 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('roles.insertar') }}" class="p-6 bg-white dark:bg-gray-900">
        @csrf
        <div class="mb-4">
            <label for="NOMBRE_ROL" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Rol</label>
            <input type="text" name="NOMBRE_ROL" id="NOMBRE_ROL" class="mt-1 block w-full px-3 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ old('NOMBRE_ROL') }}" required>
        </div>
        <div class="mb-4">
            <label for="DESCRIPCION_ROL" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
            <textarea name="DESCRIPCION_ROL" id="DESCRIPCION_ROL" class="mt-1 block w-full px-3 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('DESCRIPCION_ROL') }}</textarea>
        </div>
        <div class="flex space-x-4">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">Guardar</button>
            <a href="{{ route('roles.listar') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Cancelar</a>
        </div>
    </form>
</div>
@endsection