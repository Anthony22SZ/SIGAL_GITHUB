<!-- resources/views/objetos/index.blade.php -->
@extends('layouts.app')

@section('title', 'Objetos - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3">PANTALLAS</h1>
    @if (session('success'))
        <div class="px-6 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
        <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar proveedores...">
            </div>
        </div>
        @canInsert('Pantalla')
    <div class="px-6 py-3 flex justify-end">
        <a href="{{ route('objetos.crear') }}" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">Crear Pantalla</a>
    </div>
@endCanInsert
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all" class="sr-only">Seleccionar todos</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">Código Objeto</th>
                <th scope="col" class="px-6 py-3">Nombre Objeto</th>
                <th scope="col" class="px-6 py-3">Descripción Objeto</th>
                <th scope="col" class="px-6 py-3">Usuario Crea</th>
                <th scope="col" class="px-6 py-3">Fecha Crea</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (!empty($objetos) && count($objetos) > 0)
            @foreach($objetos as $objeto)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-{{ $objeto['COD_OBJETO'] ?? 'undefined' }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-{{ $objeto['COD_OBJETO'] ?? 'undefined' }}" class="sr-only">Seleccionar</label>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $objeto['COD_OBJETO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $objeto['NOMBRE_OBJETO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $objeto['DESCRIPCION_OBJETO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $objeto['USUARIO_CREA'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $objeto['FECHA_CREA'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay objetos registrados.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<script>
    document.getElementById('table-search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const columns = row.getElementsByTagName('td');
            let rowMatches = false;
            
            for (let i = 0; i < columns.length - 1; i++) { // Excluimos la columna de acciones
                const cellText = columns[i].innerText.toLowerCase();
                if (cellText.includes(searchValue)) {
                    rowMatches = true;
                    break;
                }
            }
            
            if (rowMatches) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection