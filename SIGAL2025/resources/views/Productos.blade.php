@extends('layouts.app')

@section('title', 'Productos - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3 dark:bg-gray-900 text-2xl font-semibold text-gray-900 dark:text-white">PRODUCTOS</h1>
    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar productos por código o modelo...">
            </div>
        </div>
        <div>
        <a href="{{ route('productos.crear') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Crear Producto</a> 
    </div>
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
                <th scope="col" class="px-6 py-3">Código</th>
                <th scope="col" class="px-6 py-3">Modelo</th>
                <th scope="col" class="px-6 py-3">Descripción</th>
                <th scope="col" class="px-6 py-3">Categoría</th>
                <th scope="col" class="px-6 py-3">Garantía</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (is_array($productos) && count($productos) > 0)
            @foreach($productos as $producto)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-{{ $producto['COD_PRODUCTO'] ?? 'undefined' }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-{{ $producto['COD_PRODUCTO'] ?? 'undefined' }}" class="sr-only">Seleccionar</label>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $producto['CODIGO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $producto['MODELO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $producto['DESCRIPCION'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $producto['NOMBRE_CATEGORIA'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $producto['TIEMPO_GARANTIA'] ?? 'N/A' }} meses</td>
                    <td class="px-6 py-4">
    <div class="flex items-center space-x-2">
        <div>
            <a href="{{ route('productos.editar', $producto['COD_PRODUCTO']) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                </svg>
            </a>
        </div>
        <div>
            <form action="{{ route('productos.eliminar', $producto['COD_PRODUCTO']) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-7 0h10"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay productos registrados.
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
            
            row.style.display = rowMatches ? '' : 'none';
        });
    });
</script>
@endsection
