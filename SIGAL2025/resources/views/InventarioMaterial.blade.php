@extends('layouts.app')

@section('title', 'Inventario - SIGAL')

@section('content')
<div id="inventario-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h1 class="px-6 py-3 dark:bg-gray-900">INVENTARIO</h1>
    <div class="pb-4 bg-white dark:bg-gray-900 flex justify-between items-center">
        <div>
            <label for="table-search" class="sr-only">Buscar</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="table-search" class="block w-80 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar materiales...">
            </div>
        </div>
    </div>

    <!-- Mostrar mensaje de error si existe -->
    @if (isset($error))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ $error }}
        </div>
    @endif

    <table id="tabla-inventario" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all" class="sr-only">Seleccionar todos</label>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">Código Material</th>
                <th scope="col" class="px-6 py-3">Nombre Material</th>
                <th scope="col" class="px-6 py-3">Stock Actual</th>
                <th scope="col" class="px-6 py-3">Stock Mínimo</th>
                <th scope="col" class="px-6 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @if (is_array($inventario) && count($inventario) > 0)
            @foreach($inventario as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-codigo="{{ $item['MATERIAL_CODIGO'] ?? 'N/A' }}">
                    <td class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-{{ $item['MATERIAL_CODIGO'] ?? 'undefined' }}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-{{ $item['MATERIAL_CODIGO'] ?? 'undefined' }}" class="sr-only">Seleccionar</label>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item['MATERIAL_CODIGO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $item['NOMBRE_MATERIAL'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $item['STOCK_ACTUAL'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $item['STOCK_MINIMO'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <button type="button" class="salida-btn text-blue-600 hover:underline" data-codigo="{{ $item['MATERIAL_CODIGO'] ?? 'N/A' }}">Salida Material</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No hay materiales registrados.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<!-- Modal de Flowbite -->
<div id="salida-modal" tabindex="-1" class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden flex justify-center items-center">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Registrar Salida de Material</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="salida-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <form id="form-salida" class="p-4">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Material</label>
                    <input type="text" id="modal-codigo" name="codigo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" readonly>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
                    <input type="number" name="cantidad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Ingrese la cantidad" required>
                </div>
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar Salida</button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Fondo del modal con transparencia */
    #salida-modal {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #salida-modal:not(.hidden) ~ #inventario-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #inventario-container {
        transition: filter 0.3s ease;
    }
</style>

<script>
    // Búsqueda en la tabla
    document.getElementById('table-search').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabla-inventario tbody tr');
        
        rows.forEach(row => {
            const columns = row.getElementsByTagName('td');
            let rowMatches = false;
            
            for (let i = 0; i < columns.length; i++) {
                const cellText = columns[i].innerText.toLowerCase();
                if (cellText.includes(searchValue)) {
                    rowMatches = true;
                    break;
                }
            }
            
            row.style.display = rowMatches ? '' : 'none';
        });
    });

    // Manejar clics en "Salida Material"
    const salidaButtons = document.querySelectorAll('.salida-btn');
    const modal = document.getElementById('salida-modal');
    const modalCodigo = document.getElementById('modal-codigo');
    const formSalida = document.getElementById('form-salida');

    salidaButtons.forEach(button => {
        button.addEventListener('click', function() {
            const codigo = this.getAttribute('data-codigo');
            modalCodigo.value = codigo;
            modal.classList.remove('hidden');
        });
    });

    // Cerrar modal con el botón "X"
    document.querySelector('[data-modal-hide="salida-modal"]').addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Enviar solicitud de salida
    formSalida.addEventListener('submit', async function(e) {
        e.preventDefault();

        const codigo = modalCodigo.value;
        const cantidad = document.querySelector('#form-salida input[name="cantidad"]').value;

        try {
            const response = await fetch('/salida-material', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ codigo, cantidad })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                actualizarTabla(codigo, cantidad);
                modal.classList.add('hidden');
                formSalida.reset();
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al registrar la salida');
        }
    });

    // Actualizar la tabla
    function actualizarTabla(codigo, cantidad) {
        const fila = document.querySelector(`#tabla-inventario tr[data-codigo="${codigo}"]`);
        if (fila) {
            const celdaStock = fila.cells[3];
            const stockActual = parseInt(celdaStock.textContent);
            const nuevaCantidad = stockActual - parseInt(cantidad);

            if (nuevaCantidad >= 0) {
                celdaStock.textContent = nuevaCantidad;
            } else {
                alert('No hay suficiente stock para esta salida');
            }
        }
    }
</script>
@endsection