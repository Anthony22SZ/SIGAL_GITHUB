@extends('layouts.app')

@section('title', 'CAIs')

@section('content')
<div id="cai-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        <a href="{{ route('puntos-emision.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Puntos de Emisión</a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('tipos-documento.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Tipos de Documento</a>
                    </li>
                </ul>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Configuración de CAI</h2>
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
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                                                </svg>
                                            </a>
                                            <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-7 0h10"></path>
                                                </svg>
                                            </a>
                                        </div>
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
</div>
                <!-- Modal para Crear CAI -->
                <div id="crear-cai-modal" tabindex="-1" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
                    <div class="relative w-full max-w-2xl p-4">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nuevo CAI</h3>
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
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Ingresa la información del nuevo CAI</p>
                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                        <div>
                                            <label for="codigo_cai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código CAI</label>
                                            <input type="text" name="codigo_cai" id="codigo_cai" placeholder="Ej. 275CBE-3B770E-A4FFE0-63BE03-09097A-4C" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="37">
                                            <span id="codigo_cai_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                    <div class="grid grid-cols-3 gap-4 mb-4">    
                                        <div>
                                            <label for="fecha_emision" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Emisión</label>
                                            <input type="date" name="fecha_emision" id="fecha_emision" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required min="{{ date('Y-m-d') }}">
                                            <span id="fecha_emision_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="fecha_vencimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Vencimiento</label>
                                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                            <span id="fecha_vencimiento_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="cod_tipo_documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Documento</label>
                                            <select name="cod_tipo_documento" id="cod_tipo_documento" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                                <option value="">Selecciona un tipo de documento</option>
                                                @foreach ($tiposDocumento as $tipo)
                                                    <option value="{{ $tipo['COD_TIPO_DOCUMENTO'] }}">{{ $tipo['NOMBRE'] }} ({{ $tipo['CODIGO'] }})</option>
                                                @endforeach
                                            </select>
                                            <span id="cod_tipo_documento_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label for="cod_punto_emision" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Punto de Emisión</label>
                                            <select name="cod_punto_emision" id="cod_punto_emision" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                                <option value="">Selecciona un punto de emisión</option>
                                                @foreach ($puntosEmision as $punto)
                                                    <option value="{{ $punto['COD_PUNTO_EMISION'] }}">{{ $punto['NOMBRE'] }} ({{ $punto['CODIGO'] }})</option>
                                                @endforeach
                                            </select>
                                            <span id="cod_punto_emision_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="establecimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Establecimiento</label>
                                            <input type="text" name="establecimiento" id="establecimiento" placeholder="Ej. 001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="3" minlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <span id="establecimiento_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="punto_emision_codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Punto de Emisión</label>
                                            <input type="text" name="punto_emision_codigo" id="punto_emision_codigo" placeholder="Ej. 001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="3" minlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <span id="punto_emision_codigo_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-3 gap-4">
                                       
                                        <div>
                                            <label for="tipo_documento_codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Tipo de Documento</label>
                                            <input type="text" name="tipo_documento_codigo" id="tipo_documento_codigo" placeholder="Ej. 01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="2" minlength="2" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <span id="tipo_documento_codigo_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="rango_inicial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rango Inicial</label>
                                            <input type="text" name="rango_inicial" id="rango_inicial" placeholder="Ej. 00000001" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="8" minlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <span id="rango_inicial_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="rango_final" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rango Final</label>
                                            <input type="text" name="rango_final" id="rango_final" placeholder="Ej. 00001000" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required maxlength="8" minlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <span id="rango_final_error" class="text-red-500 text-xs mt-1 hidden"></span>
                                        </div>
                                    </div>
                                   
                                </div>
                                <!-- Modal footer -->
                                <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                    <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

<style>
    /* Fondo del modal con transparencia */
    #crear-cai-modal {
        background: rgba(0, 0, 0, 0.5);
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #crear-cai-modal:not(.hidden) ~ #cai-container {
        filter: blur(4px);
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #cai-container {
        transition: filter 0.3s ease;
    }
</style>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const codigoCaiInput = document.getElementById('codigo_cai');
            const errorSpan = document.getElementById('codigo_cai_error');

            // Función para formatear el CAI
            function formatearCAI(input) {
                let valor = input.value.replace(/[^A-F0-9]/gi, '').toUpperCase();
                if (valor.length > 32) {
                    valor = valor.slice(0, 32);
                }
                let formatted = '';
                for (let i = 0; i < valor.length; i++) {
                    if (i === 6 || i === 12 || i === 18 || i === 24 || i === 30) {
                        formatted += '-';
                    }
                    formatted += valor[i];
                }
                input.value = formatted;
            }

            // Validar que cada bloque de 6 caracteres tenga al menos una letra
            function validarLetrasPorBloque(cai) {
                const bloques = cai.split('-');
                for (let i = 0; i < 5; i++) {
                    if (!/[A-F]/.test(bloques[i])) {
                        return { valido: false, bloque: i + 1 };
                    }
                }
                return { valido: true };
            }

            // Evento de entrada para formatear y validar en tiempo real
            codigoCaiInput.addEventListener('input', function () {
                formatearCAI(this);

                const caiRegex = /^([A-F0-9]{6}-){5}[A-F0-9]{2}$/i;
                if (this.value.length > 0) {
                    if (this.value.length !== 37) {
                        errorSpan.textContent = 'El CAI debe tener exactamente 37 caracteres (incluyendo guiones)';
                        errorSpan.classList.remove('hidden');
                    } else if (!caiRegex.test(this.value)) {
                        errorSpan.textContent = 'Formato inválido (ej. 275CBE-3B770E-A4FFE0-63BE03-09097A-4C)';
                        errorSpan.classList.remove('hidden');
                    } else {
                        const resultado = validarLetrasPorBloque(this.value);
                        if (!resultado.valido) {
                            errorSpan.textContent = `El bloque ${resultado.bloque} debe contener al menos una letra (A-F)`;
                            errorSpan.classList.remove('hidden');
                        } else {
                            errorSpan.classList.add('hidden');
                        }
                    }
                } else {
                    errorSpan.classList.add('hidden');
                }
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

            // Validación del formulario antes de enviar
            document.getElementById('crear-cai-form').addEventListener('submit', function (e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);

                const codigoCai = document.getElementById('codigo_cai').value.trim();
                const fechaEmision = document.getElementById('fecha_emision').value;
                const fechaVencimiento = document.getElementById('fecha_vencimiento').value;
                const codTipoDocumento = document.getElementById('cod_tipo_documento').value;
                const codPuntoEmision = document.getElementById('cod_punto_emision').value;
                const establecimiento = document.getElementById('establecimiento').value.trim();
                const puntoEmisionCodigo = document.getElementById('punto_emision_codigo').value.trim();
                const tipoDocumentoCodigo = document.getElementById('tipo_documento_codigo').value.trim();
                const rangoInicial = document.getElementById('rango_inicial').value.trim();
                const rangoFinal = document.getElementById('rango_final').value.trim();

                // Limpiar errores previos
                document.querySelectorAll('.text-red-500').forEach(span => {
                    span.classList.add('hidden');
                    span.textContent = '';
                });

                // Validar Código CAI
                const caiRegex = /^([A-F0-9]{6}-){5}[A-F0-9]{2}$/i;
                if (!caiRegex.test(codigoCai)) {
                    errorSpan.textContent = 'El Código CAI debe tener el formato correcto (ej. 275CBE-3B770E-A4FFE0-63BE03-09097A-4C)';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('codigo_cai').focus();
                    return;
                }

                const resultado = validarLetrasPorBloque(codigoCai);
                if (!resultado.valido) {
                    errorSpan.textContent = `El bloque ${resultado.bloque} debe contener al menos una letra (A-F)`;
                    errorSpan.classList.remove('hidden');
                    document.getElementById('codigo_cai').focus();
                    return;
                }

                // Validar Fecha de Emisión
                const today = new Date().toISOString().split('T')[0];
                if (!fechaEmision || fechaEmision < today) {
                    const errorSpan = document.getElementById('fecha_emision_error');
                    errorSpan.textContent = fechaEmision ? 'La Fecha de Emisión no puede ser anterior a hoy (' + today + ')' : 'La Fecha de Emisión es obligatoria';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('fecha_emision').focus();
                    return;
                }

                // Validar Fecha de Vencimiento
                if (!fechaVencimiento || fechaVencimiento <= fechaEmision) {
                    const errorSpan = document.getElementById('fecha_vencimiento_error');
                    errorSpan.textContent = fechaVencimiento ? 'La Fecha de Vencimiento debe ser posterior a la Fecha de Emisión (' + fechaEmision + ')' : 'La Fecha de Vencimiento es obligatoria';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('fecha_vencimiento').focus();
                    return;
                }

                // Validar Tipo de Documento
                if (!codTipoDocumento) {
                    const errorSpan = document.getElementById('cod_tipo_documento_error');
                    errorSpan.textContent = 'Debe seleccionar un Tipo de Documento';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('cod_tipo_documento').focus();
                    return;
                }

                // Validar Punto de Emisión
                if (!codPuntoEmision) {
                    const errorSpan = document.getElementById('cod_punto_emision_error');
                    errorSpan.textContent = 'Debe seleccionar un Punto de Emisión';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('cod_punto_emision').focus();
                    return;
                }

                // Validar Establecimiento
                if (!/^\d{3}$/.test(establecimiento)) {
                    const errorSpan = document.getElementById('establecimiento_error');
                    errorSpan.textContent = 'El Establecimiento debe ser un código de 3 dígitos';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('establecimiento').focus();
                    return;
                }

                // Validar Punto de Emisión Código
                if (!/^\d{3}$/.test(puntoEmisionCodigo)) {
                    const errorSpan = document.getElementById('punto_emision_codigo_error');
                    errorSpan.textContent = 'El Código del Punto de Emisión debe ser un código de 3 dígitos';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('punto_emision_codigo').focus();
                    return;
                }

                // Validar Tipo de Documento Código
                if (!/^\d{2}$/.test(tipoDocumentoCodigo)) {
                    const errorSpan = document.getElementById('tipo_documento_codigo_error');
                    errorSpan.textContent = 'El Código del Tipo de Documento debe ser un código de 2 dígitos';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('tipo_documento_codigo').focus();
                    return;
                }

                // Validar Rango Inicial
                const rangoRegex = /^\d{8}$/;
                if (!rangoRegex.test(rangoInicial)) {
                    const errorSpan = document.getElementById('rango_inicial_error');
                    errorSpan.textContent = 'El Rango Inicial debe ser un número de 8 dígitos';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('rango_inicial').focus();
                    return;
                }
                const rangoInicialNum = parseInt(rangoInicial);
                if (rangoInicialNum < 1 || rangoInicialNum > 99999999) {
                    const errorSpan = document.getElementById('rango_inicial_error');
                    errorSpan.textContent = 'El Rango Inicial debe estar entre 00000001 y 99999999';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('rango_inicial').focus();
                    return;
                }

                // Validar Rango Final
                if (!rangoRegex.test(rangoFinal)) {
                    const errorSpan = document.getElementById('rango_final_error');
                    errorSpan.textContent = 'El Rango Final debe ser un número de 8 dígitos';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('rango_final').focus();
                    return;
                }
                const rangoFinalNum = parseInt(rangoFinal);
                if (rangoFinalNum < 1 || rangoFinalNum > 99999999) {
                    const errorSpan = document.getElementById('rango_final_error');
                    errorSpan.textContent = 'El Rango Final debe estar entre 00000001 y 99999999';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('rango_final').focus();
                    return;
                }

                if (rangoFinalNum <= rangoInicialNum) {
                    const errorSpan = document.getElementById('rango_final_error');
                    errorSpan.textContent = 'El Rango Final debe ser mayor que el Rango Inicial (' + rangoInicial + ')';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('rango_final').focus();
                    return;
                }

                // Enviar el formulario
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
                        const modal = document.getElementById('crear-cai-modal');
                        modal.classList.add('hidden');
                        window.location.reload();
                    } else {
                        let mensajeError = 'Error al crear el CAI. Por favor, verifica los datos ingresados.';
                        if (data.error) {
                            if (data.error.includes('BIGINT UNSIGNED value is out of range')) {
                                mensajeError = 'El Rango Inicial o Final excede los límites permitidos. Asegúrate de que sean valores válidos entre 00000001 y 99999999.';
                            } else if (data.error.includes('duplicate entry')) {
                                mensajeError = 'El Código CAI ya existe en el sistema. Ingresa un código diferente.';
                            } else {
                                mensajeError = data.message || data.error || mensajeError;
                            }
                        }
                        errorSpan.textContent = mensajeError;
                        errorSpan.classList.remove('hidden');
                        document.getElementById('codigo_cai').focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorSpan.textContent = 'Ocurrió un error al procesar la solicitud. Intenta de nuevo más tarde.';
                    errorSpan.classList.remove('hidden');
                    document.getElementById('codigo_cai').focus();
                });
            });

            // Manejo del modal (abrir/cerrar)
            const modalToggle = document.querySelector('[data-modal-toggle="crear-cai-modal"]');
            const modal = document.getElementById('crear-cai-modal');
            const modalClose = document.querySelector('[data-modal-hide="crear-cai-modal"]');

            modalToggle.addEventListener('click', () => {
                modal.classList.toggle('hidden');
                document.querySelectorAll('.text-red-500').forEach(span => {
                    span.classList.add('hidden');
                    span.textContent = '';
                });
            });

            modalClose.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
@endsection