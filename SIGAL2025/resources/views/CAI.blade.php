@extends('layouts.app')

@section('title', 'CAIs')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <!-- Título y descripción -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">CONFIGURACIÓN FISCAL</h1>
                <p class="text-gray-500 dark:text-gray-400">Gestiona la configuración fiscal y CAI para Honduras</p>
            </div>
        </div>

           <!-- Pestañas de navegación -->
      <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">CAI</a>
                </li>
                <li class="me-2">
                    <a href="{{ route('empresa.mostrar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Datos de Empresa</a>
                </li>
                <li class="me-2">
                    <a href="{{route('puntos-emision.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Puntos de Emisión</a>
                </li>
                <li class="me-2">
                    <a href="{{ route('tipos-documento.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Tipos de Documento</a>
                </li>
            </ul>
        </div>

    
        <div class="mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Configuracion de CAI</h2>
                <!-- Botón para abrir el modal -->
                <button data-modal-target="crear-cai-modal" data-modal-toggle="crear-cai-modal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                     Nuevo CAI
                </button>
            </div>
            <p class="text-gray-600 mb-4">Gestiona los Códigos de Autorización de Impresión (CAI) otorgados por el SAR</p>

    <!-- Tabla de CAIs -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Código CAI</th>
                    <th scope="col" class="px-6 py-3">Fecha Emisión</th>
                    <th scope="col" class="px-6 py-3">Fecha Vencimiento</th>
                    <th scope="col" class="px-6 py-3">Tipo de Documento</th>
                    <th scope="col" class="px-6 py-3">Punto de Emisión</th>
                    <th scope="col" class="px-6 py-3">Establecimiento</th>
                    <th scope="col" class="px-6 py-3">Rango Inicial</th>
                    <th scope="col" class="px-6 py-3">Rango Final</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cais as $cai)
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ $cai['CODIGO_CAI'] }}</td>
                        <td class="px-6 py-4">{{ $cai['FECHA_EMISION'] }}</td>
                        <td class="px-6 py-4">{{ $cai['FECHA_VENCIMIENTO'] }}</td>
                        <td class="px-6 py-4">{{ $cai['TIPO_DOCUMENTO_CODIGO'] }}</td>
                        <td class="px-6 py-4">{{ $cai['PUNTO_EMISION_CODIGO'] }}</td>
                        <td class="px-6 py-4">{{ $cai['ESTABLECIMIENTO'] }}</td>
                        <td class="px-6 py-4">{{ $cai['RANGO_INICIAL'] }}</td>
                        <td class="px-6 py-4">{{ $cai['RANGO_FINAL'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                {{ $cai['ESTADO'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- Botón de Editar -->
                            <a href="#" class="text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                                </svg>
                            </a>
                            <!-- Botón de Eliminar -->
                            <a href="#" class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M4 7h16"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">No hay CAIs disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal para Crear CAI -->
    <div id="crear-cai-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Nuevo CAI
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="crear-cai-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Cerrar modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="crear-cai-form" action="{{ route('cai.crear') }}" method="POST">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        <p class="text-sm text-gray-600">Ingresa la información del nuevo CAI</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="codigo_cai" class="block mb-2 text-sm font-medium text-gray-900">Código CAI</label>
                                <input type="text" name="codigo_cai" id="codigo_cai" placeholder="Código del CAI" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div>
                                <label for="fecha_emision" class="block mb-2 text-sm font-medium text-gray-900">Fecha de Emisión</label>
                                <input type="date" name="fecha_emision" id="fecha_emision" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_vencimiento" class="block mb-2 text-sm font-medium text-gray-900">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div>
                                <label for="cod_tipo_documento" class="block mb-2 text-sm font-medium text-gray-900">Tipo de Documento</label>
                                <select name="cod_tipo_documento" id="cod_tipo_documento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Selecciona un tipo de documento</option>
                                    @foreach ($tiposDocumento as $tipo)
                                        <option value="{{ $tipo['COD_TIPO_DOCUMENTO'] }}">{{ $tipo['NOMBRE'] }} ({{ $tipo['CODIGO'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="cod_punto_emision" class="block mb-2 text-sm font-medium text-gray-900">Punto de Emisión</label>
                                <select name="cod_punto_emision" id="cod_punto_emision" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Selecciona un punto de emisión</option>
                                    @foreach ($puntosEmision as $punto)
                                        <option value="{{ $punto['COD_PUNTO_EMISION'] }}">{{ $punto['NOMBRE'] }} ({{ $punto['CODIGO'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="establecimiento" class="block mb-2 text-sm font-medium text-gray-900">Establecimiento</label>
                                <input type="text" name="establecimiento" id="establecimiento" placeholder="Establecimiento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="punto_emision_codigo" class="block mb-2 text-sm font-medium text-gray-900">Código Punto de Emisión</label>
                                <input type="text" name="punto_emision_codigo" id="punto_emision_codigo" placeholder="Código del punto de emisión" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-25" required>
                            </div>
                            <div>
                                <label for="tipo_documento_codigo" class="block mb-2 text-sm font-medium text-gray-900">Código Tipo de Documento</label>
                                <input type="text" name="tipo_documento_codigo" id="tipo_documento_codigo" placeholder="Código del tipo de documento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="rango_inicial" class="block mb-2 text-sm font-medium text-gray-900">Rango Inicial</label>
                                <input type="text" name="rango_inicial" id="rango_inicial" placeholder="Rango inicial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div>
                                <label for="rango_final" class="block mb-2 text-sm font-medium text-gray-900">Rango Final</label>
                                <input type="text" name="rango_final" id="rango_final" placeholder="Rango final" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('crear-cai-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    alert(data.message || 'CAI creado exitosamente');
                    // Cerrar el modal
                    const modal = document.getElementById('crear-cai-modal');
                    modal.classList.add('hidden');
                    // Recargar la página para actualizar la tabla
                    window.location.reload();
                } else {
                    // Mostrar mensaje de error
                    alert(data.message || 'Error al crear el CAI');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        });

        // Sincronizar los campos de código con las selecciones
        document.getElementById('cod_punto_emision').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const codigo = selectedOption.textContent.match(/\(([^)]+)\)/)?.[1] || '';
            document.getElementById('punto_emision_codigo').value = codigo;
        });

        document.getElementById('cod_tipo_documento').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const codigo = selectedOption.textContent.match(/\(([^)]+)\)/)?.[1] || '';
            document.getElementById('tipo_documento_codigo').value = codigo;
        });
    </script>
@endsection