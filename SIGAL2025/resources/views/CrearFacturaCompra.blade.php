@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-4">Registrar Factura de Compra</h2>

    <!-- Mostrar mensajes de éxito o error -->
    @if(session('success'))
        <div id="success-message" style="color: green;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('FacturaCompra.insertar') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <!-- Datos de la Factura -->
            <div class="w-full">
                <label for="cod_proveedor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Proveedor</label>
                <select name="cod_proveedor" id="cod_proveedor" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="" selected disabled>Seleccionar Proveedor</option>
                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor['COD_PROVEEDORES'] }}">{{ $proveedor['NOMBRE_EMPRESA'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full">
                <label for="numero_factura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número Factura</label>
                <input type="text" name="numero_factura" id="numero_factura" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Número Factura" required maxlength="50" minlength="3">
            </div>
            <div class="w-full">
                <label for="impuesto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Impuesto (%)</label>
                <input type="number" name="impuesto" id="impuesto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Impuesto" min="0" max="100" step="0.01" required>
            </div>
            <div class="w-full">
                <label for="descuento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descuento ($)</label>
                <input type="number" id="descuento" name="descuento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Descuento" min="0" step="0.01" value="0" required>
            </div>
        </div>
        <hr class="my-4">
        <!-- Detalles de la Compra -->
        <h3 class="text-xl font-medium mb-4">Detalles de Compra</h3>
        <div id="detalle-container" class="space-y-4">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="detallesTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Producto</th>
                        <th scope="col" class="px-6 py-3">Cantidad</th>
                        <th scope="col" class="px-6 py-3">Precio Unitario</th>
                        <th scope="col" class="px-6 py-3">Subtotal</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="detallesBody">
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" id="detalle-row-0">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="detalles[0][material_codigo]" required>
                                <option value="">Seleccione un material</option>
                                @foreach($materiales as $material)
                                    <option value="{{ $material['CODIGO'] }}">{{ $material['MATERIAL'] }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="detalles[0][cantidad]" min="1" value="1" required>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" name="detalles[0][precio]" step="0.01" min="0" required>
                        </td>
                        <td class="px-6 py-4">
                            <span class="subtotal">0.00</span>
                        </td>
                        <td class="px-6 py-4">
                            <button type="button" class="remove-item font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button id="add-detalle" type="button" class="flex items-center text-green-600 hover:bg-green-100 px-2 py-1 rounded">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span class="ml-2">Agregar Material</span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Resumen de la Factura -->
        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subtotal</label>
                    <input type="text" id="subtotal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descuento</label>
                    <input type="text" id="descuentoCalculado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Impuesto</label>
                    <input type="text" id="impuestoCalculado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                    <input type="text" id="total" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly>
                </div>
            </div>
        </div>
        <!-- Botones de Acción -->
        <div class="mt-6 flex justify-end space-x-4">
            <button type="button" class="px-6 py-2 bg-gray-500 text-white rounded-md" onclick="window.location.href='{{ route('FacturaCompra.crear') }}'">Cancelar</button>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md">Guardar Factura</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function actualizarSubtotal(row) {
        const cantidad = parseFloat(row.querySelector('input[name*="cantidad"]').value) || 0;
        const precio = parseFloat(row.querySelector('input[name*="precio"]').value) || 0;
        const subtotal = (cantidad * precio).toFixed(2);
        row.querySelector('.subtotal').textContent = subtotal;
    }

    function calcularTotal() {
        const subtotales = document.querySelectorAll('.subtotal');
        let subtotalGeneral = 0;

        subtotales.forEach(subtotal => {
            subtotalGeneral += parseFloat(subtotal.textContent) || 0;
        });

        const impuesto = parseFloat(document.getElementById('impuesto').value) || 0;
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;

        const impuestoCalculado = (subtotalGeneral * impuesto) / 100;
        const total = (subtotalGeneral + impuestoCalculado) - descuento;

        document.getElementById('subtotal').value = subtotalGeneral.toFixed(2);
        document.getElementById('descuentoCalculado').value = descuento.toFixed(2);
        document.getElementById('impuestoCalculado').value = impuestoCalculado.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
    }

    function agregarFila() {
        const detallesBody = document.getElementById('detallesBody');
        const index = detallesBody.rows.length;

        const newRow = document.createElement('tr');
        newRow.classList.add('bg-white', 'border-b', 'dark:bg-gray-800', 'dark:border-gray-700', 'hover:bg-gray-50', 'dark:hover:bg-gray-600');
        newRow.innerHTML = `
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <select name="detalles[${index}][material_codigo]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="">Seleccione un material</option>
                    @foreach($materiales as $material)
                        <option value="{{ $material['CODIGO'] }}">{{ $material['MATERIAL'] }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="detalles[${index}][cantidad]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" min="1" value="1" required>
            </td>
            <td class="px-6 py-4">
                <input type="number" name="detalles[${index}][precio]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" step="0.01" min="0" required>
            </td>
            <td class="px-6 py-4">
                <span class="subtotal">0.00</span>
            </td>
            <td class="px-6 py-4">
                <button type="button" class="remove-item font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</button>
            </td>
        `;

        detallesBody.appendChild(newRow);

        // Eventos para la nueva fila
        newRow.querySelector('input[name*="cantidad"]').addEventListener('input', function() {
            actualizarSubtotal(newRow);
            calcularTotal();
        });

        newRow.querySelector('input[name*="precio"]').addEventListener('input', function() {
            actualizarSubtotal(newRow);
            calcularTotal();
        });

        newRow.querySelector('.remove-item').addEventListener('click', function() {
            detallesBody.removeChild(newRow);
            calcularTotal();
        });

        actualizarSubtotal(newRow);
        calcularTotal();
    }

    // Inicializar eventos para la primera fila
    document.querySelectorAll('#detallesBody tr').forEach(row => {
        actualizarSubtotal(row);

        row.querySelector('input[name*="cantidad"]').addEventListener('input', function() {
            actualizarSubtotal(row);
            calcularTotal();
        });

        row.querySelector('input[name*="precio"]').addEventListener('input', function() {
            actualizarSubtotal(row);
            calcularTotal();
        });

        row.querySelector('.remove-item').addEventListener('click', function() {
            document.getElementById('detallesBody').removeChild(row);
            calcularTotal();
        });
    });

    // Eventos para impuesto y descuento
    document.getElementById('impuesto').addEventListener('input', calcularTotal);
    document.getElementById('descuento').addEventListener('input', calcularTotal);

    // Evento para agregar fila
    document.getElementById('add-detalle').addEventListener('click', agregarFila);

    // Calcular total inicial
    calcularTotal();

    document.addEventListener('DOMContentLoaded', function() {
            // Ocultar el mensaje de éxito después de 5 segundos
            setTimeout(function() {
                var successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
            }, 5000); // 5000 milisegundos = 5 segundos
        });

</script>
@endsection