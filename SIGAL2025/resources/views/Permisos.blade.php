<!-- resources/views/permisos/index.blade.php -->
@extends('layouts.app')

@section('title', 'Permisos - SIGAL')

@section('content')
<div class="p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Gestión de Permisos</h1>

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

    <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-lg">
        <p><strong>Información sobre permisos:</strong> Marca las casillas para otorgar permisos específicos. El permiso "Ver" es necesario para cualquier otra acción. Si desactivas "Ver", se desactivarán automáticamente todos los demás permisos.</p>
    </div>

    <div class="mb-6">
        <label for="cod_rol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seleccionar Rol</label>
        <select name="cod_rol" id="cod_rol" class="mt-1 block w-full px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach ($roles as $rol)
                <option value="{{ $rol['COD_ROL'] }}" {{ $codRol == $rol['COD_ROL'] ? 'selected' : '' }}>{{ $rol['NOMBRE_ROL'] }}</option>
            @endforeach
        </select>
    </div>

    <form method="POST" action="{{ route('permisos.guardar') }}" id="permisos-form">
        @csrf
        <input type="hidden" name="cod_rol" id="form_cod_rol" value="{{ $codRol }}">

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="permisos-table">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Pantalla</th>
                        <th scope="col" class="px-6 py-3">Ver</th>
                        <th scope="col" class="px-6 py-3">Crear</th>
                        <th scope="col" class="px-6 py-3">Editar</th>
                        <th scope="col" class="px-6 py-3">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($pantallas as $pantalla)
    @php
        $permiso = collect($permisos)->firstWhere('COD_OBJETO', $pantalla['COD_OBJETO']);
    @endphp
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-cod-objeto="{{ $pantalla['COD_OBJETO'] }}">
        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{ $pantalla['NOMBRE_OBJETO'] }}<br>
            <span class="text-xs text-gray-500 dark:text-gray-400">/{{ strtolower($pantalla['NOMBRE_OBJETO']) }}</span>
        </td>
        <td class="px-6 py-4">
            <!-- Campo oculto para enviar 0 si el checkbox no está marcado -->
            <input type="hidden" name="permisos[{{ $pantalla['COD_OBJETO'] }}][ver]" value="0">
            <input type="checkbox" name="permisos[{{ $pantalla['COD_OBJETO'] }}][ver]" value="1" class="ver-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $permiso && $permiso['ESTADO_SELECCION'] == '1' ? 'checked' : '' }}>
        </td>
        <td class="px-6 py-4">
            <!-- Campo oculto para enviar 0 si el checkbox no está marcado -->
            <input type="hidden" name="permisos[{{ $pantalla['COD_OBJETO'] }}][crear]" value="0">
            <input type="checkbox" name="permisos[{{ $pantalla['COD_OBJETO'] }}][crear]" value="1" class="crear-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $permiso && $permiso['ESTADO_INSERCION'] == '1' ? 'checked' : '' }}>
        </td>
        <td class="px-6 py-4">
            <!-- Campo oculto para enviar 0 si el checkbox no está marcado -->
            <input type="hidden" name="permisos[{{ $pantalla['COD_OBJETO'] }}][editar]" value="0">
            <input type="checkbox" name="permisos[{{ $pantalla['COD_OBJETO'] }}][editar]" value="1" class="editar-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $permiso && $permiso['ESTADO_ACTUALIZACION'] == '1' ? 'checked' : '' }}>
        </td>
        <td class="px-6 py-4">
            <!-- Campo oculto para enviar 0 si el checkbox no está marcado -->
            <input type="hidden" name="permisos[{{ $pantalla['COD_OBJETO'] }}][eliminar]" value="0">
            <input type="checkbox" name="permisos[{{ $pantalla['COD_OBJETO'] }}][eliminar]" value="1" class="eliminar-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $permiso && $permiso['ESTADO_ELIMINACION'] == '1' ? 'checked' : '' }}>
        </td>
    </tr>
@endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-block px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Guardar Permisos
            </button>
        </div>
    </form>
</div>

<script>
// Actualizar la tabla de permisos al cambiar el rol
document.getElementById('cod_rol').addEventListener('change', function() {
    const codRol = this.value;
    document.getElementById('form_cod_rol').value = codRol;

    fetch(`http://localhost:3000/permisos/${codRol}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer {{ $token }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const permisos = data.data;
            updateTable(permisos);
        } else {
            console.error('Error al obtener permisos:', data.message);
            updateTable([]);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud AJAX:', error);
        updateTable([]);
    });
});

// Función para actualizar la tabla con los permisos
function updateTable(permisos) {
    const rows = document.querySelectorAll('#permisos-table tbody tr');
    rows.forEach(row => {
        const codObjeto = row.getAttribute('data-cod-objeto');
        const permiso = permisos.find(p => p.COD_OBJETO == codObjeto);

        const verCheckbox = row.querySelector('.ver-checkbox');
        const crearCheckbox = row.querySelector('.crear-checkbox');
        const editarCheckbox = row.querySelector('.editar-checkbox');
        const eliminarCheckbox = row.querySelector('.eliminar-checkbox');

        if (!permiso) {
            verCheckbox.checked = false;
            crearCheckbox.checked = false;
            editarCheckbox.checked = false;
            eliminarCheckbox.checked = false;
            crearCheckbox.disabled = true;
            editarCheckbox.disabled = true;
            eliminarCheckbox.disabled = true;
        } else {
            verCheckbox.checked = permiso.ESTADO_SELECCION == '1';
            crearCheckbox.checked = permiso.ESTADO_INSERCION == '1';
            editarCheckbox.checked = permiso.ESTADO_ACTUALIZACION == '1';
            eliminarCheckbox.checked = permiso.ESTADO_ELIMINACION == '1';

            if (!verCheckbox.checked) {
                crearCheckbox.checked = false;
                editarCheckbox.checked = false;
                eliminarCheckbox.checked = false;
                crearCheckbox.disabled = true;
                editarCheckbox.disabled = true;
                eliminarCheckbox.disabled = true;
            } else {
                crearCheckbox.disabled = false;
                editarCheckbox.disabled = false;
                eliminarCheckbox.disabled = false;
            }
        }
    });
}

// Lógica para deshabilitar checkboxes si "Ver" no está marcado
document.querySelectorAll('.ver-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const row = this.closest('tr');
        const otherCheckboxes = row.querySelectorAll('.crear-checkbox, .editar-checkbox, .eliminar-checkbox');
        if (!this.checked) {
            otherCheckboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
                // Forzar que el campo oculto tenga el valor 0
                const hiddenInput = cb.parentElement.querySelector('input[type="hidden"]');
                hiddenInput.value = '0';
            });
        } else {
            otherCheckboxes.forEach(cb => {
                cb.disabled = false;
            });
        }
    });
});

// Habilitar/deshabilitar checkboxes al cargar la página
document.querySelectorAll('.ver-checkbox').forEach(checkbox => {
    const row = checkbox.closest('tr');
    const otherCheckboxes = row.querySelectorAll('.crear-checkbox, .editar-checkbox, .eliminar-checkbox');
    if (!checkbox.checked) {
        otherCheckboxes.forEach(cb => {
            cb.disabled = true;
            // Forzar que el campo oculto tenga el valor 0
            const hiddenInput = cb.parentElement.querySelector('input[type="hidden"]');
            hiddenInput.value = '0';
        });
    }
});
</script>
@endsection