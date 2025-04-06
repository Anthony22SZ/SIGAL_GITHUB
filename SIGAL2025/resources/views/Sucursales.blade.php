@extends('layouts.app')

@section('title', 'Sucursales - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">SUCURSALES</h1>
    
    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar sucursales...">
            </div>
        </div>
        <div>
            <a href="{{ route('sucursales.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Crear Sucursal</a>
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center"> 
                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all" class="sr-only">Seleccionar todos</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">Nombre</th>
                <th scope="col" class="px-6 py-3">Dirección</th>
                <th scope="col" class="px-6 py-3">Encargado</th>
                <th scope="col" class="px-6 py-3">Teléfono</th>
                <th scope="col" class="px-6 py-3">Estado</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (count($sucursales) > 0)
            @foreach($sucursales as $sucursal)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    </td>
                    <td class="px-6 py-4">{{ $sucursal['NOMBRE_SUCURSAL'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $sucursal['DIRECCION'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $sucursal['NOMBRE_ENCARGADO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $sucursal['TELEFONO_ENCARGADO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $sucursal['ESTADO'] === 'ACTIVA' ? 'text-green-600 dark:text-green-500' : 'text-red-600 dark:text-red-500' }}">
                            {{ $sucursal['ESTADO'] ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('sucursales.edit', $sucursal['COD_SUCURSAL']) }}" 
                           class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                           Editar
                        </a>
                        <div>
                            <form action="{{ route('sucursales.toggleState', $sucursal['COD_SUCURSAL']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="{{ $sucursal['ESTADO'] }}">
                                <button type="submit" 
                                        class="font-medium {{ $sucursal['ESTADO'] === 'ACTIVA' ? 'text-red-600 dark:text-red-500' : 'text-green-600 dark:text-green-500' }} hover:underline">
                                    {{ $sucursal['ESTADO'] === 'ACTIVA' ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay sucursales registradas.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection





