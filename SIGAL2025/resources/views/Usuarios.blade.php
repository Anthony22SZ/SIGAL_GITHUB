@extends('layouts.app')

@section('title', 'Usuarios - SIGAL')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">USUARIOS</h1>
    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar usuarios...">
            </div>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('usuarios.crear') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Crear Usuario</a>
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
                <th scope="col" class="px-6 py-3">Nombre Empleado</th>
                <th scope="col" class="px-6 py-3">Nombre Rol</th>
                <th scope="col" class="px-6 py-3">Nombre Usuario</th>
                <th scope="col" class="px-6 py-3">Correo Empleado</th>
                <th scope="col" class="px-6 py-3">Estado Usuario</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (!empty($usuarios) && count($usuarios) > 0)
            @foreach($usuarios as $usuario)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-{{ $usuario['COD_USUARIO'] ?? 'undefined' }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-{{ $usuario['COD_USUARIO'] ?? 'undefined' }}" class="sr-only">Seleccionar</label>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $usuario['NOMBRE_EMPLEADO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $usuario['NOMBRE_ROL'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $usuario['NOMBRE_USUARIO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $usuario['CORREO_ELECTRONICO'] ?? 'N/A' }} ({{ $usuario['TIPO_CORREO'] ?? 'N/A' }})</td>
                    <td class="px-6 py-4">{{ $usuario['ESTADO_USUARIO'] == '1' ? 'Activo' : 'Inactivo' }}</td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                        <div>
                            <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline btn-eliminar" data-codigo="{{ $usuario['COD_USUARIO'] }}">Eliminar</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay usuarios registrados.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<!-- Meta tag CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Búsqueda en la tabla
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

    // Función para eliminar usuarios sin alerta de éxito
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', async function(event) {
                event.preventDefault();
                const codigo = this.getAttribute('data-codigo');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                console.log('Intentando eliminar usuario con código:', codigo); // Depuración

                if (!confirm(`¿Estás seguro de eliminar el usuario con código ${codigo}?`)) {
                    return;
                }

                try {
                    const response = await fetch(`/eliminar-usuario/${codigo}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    console.log('Estado de la respuesta:', response.status); // Depuración

                    const data = await response.json();

                    if (!response.ok) {
                        console.log('Datos de error:', data); // Depuración
                        throw new Error(data.error || 'Error al eliminar el usuario');
                    }

                    // Recargar la página sin alerta
                    location.reload();
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    alert(error.message || 'Ocurrió un error al eliminar el usuario');
                }
            });
        });
    });
</script>
@endsection

