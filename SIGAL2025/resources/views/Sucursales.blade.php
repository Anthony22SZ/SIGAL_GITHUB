@extends('layouts.app')

@section('title', 'Sucursales - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3 dark:bg-gray-900 text-2xl font-semibold text-gray-900 dark:text-white">SUCURSALES</h1>
    
    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar sucursales...">
            </div>
        </div>
        @canInsert('Sucursales')
        <div>
            <a href="{{ route('sucursales.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Crear Sucursal</a>
        </div>
        @endCanInsert
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
    <div class="flex justify-center items-center space-x-2">
        <!-- Editar -->
        <div class="flex items-center h-5">
            <a href="{{ route('sucursales.edit', $sucursal['COD_SUCURSAL']) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                </svg>
            </a>
        </div>
        <!-- Toggle Activar/Desactivar -->
        <div class="flex items-center h-5">
            <form action="{{ route('sucursales.toggleState', $sucursal['COD_SUCURSAL']) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <input type="hidden" name="estado" value="{{ $sucursal['ESTADO'] }}">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" 
                           class="sr-only peer" 
                           {{ $sucursal['ESTADO'] === 'ACTIVA' ? 'checked' : '' }} 
                           onchange="this.form.submit()">
                    <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:w-4 after:h-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                </label>
            </form>
        </div>
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
