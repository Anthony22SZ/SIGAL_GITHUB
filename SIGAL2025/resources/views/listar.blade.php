@extends('layouts.app')

@section('title', 'Configuración Fiscal - Puntos de Emisión - SIGAL')

@section('content')
<div id="puntos-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        <a href="{{ route('cai.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">CAI</a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('empresa.mostrar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Datos de Empresa</a>
                    </li>
                    <li class="me-2">
                        <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Puntos de Emisión</a>
                    </li>
                    <li class="me-2">
                        <a href="{{ route('tipos-documento.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Tipos de Documento</a>
                    </li>
                </ul>
            </div>

            <!-- Sección de Puntos de Emisión -->
            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Puntos de Emisión</h2>
                    <!-- Botón para abrir el modal -->
                    <button data-modal-target="crear-punto-emision-modal" data-modal-toggle="crear-punto-emision-modal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Punto de Emisión
                    </button>
                </div>
                <p class="text-gray-600 mb-4">Administra los puntos de emisión de documentos fiscales</p>

                <!-- Mensajes de éxito o error -->
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Tabla de Puntos de Emisión -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Código</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Establecimiento</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($puntosEmision as $punto)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ $punto['CODIGO'] }}</td>
                                    <td class="px-6 py-4">{{ $punto['NOMBRE'] }}</td>
                                    <td class="px-6 py-4">{{ $punto['ESTABLECIMIENTO'] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $punto['ESTADO'] === 'ACTIVO' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $punto['ESTADO'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <div class="flex justify-center items-center space-x-2">
                                            <div>  
                                                <a href="{{ route('puntos-emision.editar.form', $punto['COD_PUNTO_EMISION']) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div> 
                                                <button class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center" >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-7 0h10"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay puntos de emisión registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para Crear Punto de Emisión -->
        <div id="crear-punto-emision-modal" tabindex="-1" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80">
            <div class="relative p-4 w-full max-w-lg max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Nuevo Punto de Emisión
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="crear-punto-emision-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Cerrar modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form id="crear-punto-emision-form" action="{{ route('puntos-emision.crear') }}" method="POST">
                        @csrf
                        <div class="p-4 md:p-5 space-y-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Ingresa la información del nuevo punto de emisión</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código</label>
                                    <input type="number" name="codigo" id="codigo" placeholder="Código de 3 dígitos" 
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" 
                                           min="0" max="999" step="1" maxlength="3" oninput="this.value = this.value.slice(0, 3)" required>
                                    <span id="codigo-error" class="text-red-600 text-xs mt-1 hidden"></span>
                                </div>
                                <div>
                                    <label for="establecimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Establecimiento</label>
                                    <input type="number" name="establecimiento" id="establecimiento" placeholder="Código de establecimiento" 
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" 
                                           min="0" max="999" step="1" maxlength="3" oninput="this.value = this.value.slice(0, 3)" required>
                                    <span id="establecimiento-error" class="text-red-600 text-xs mt-1 hidden"></span>
                                </div>
                            </div>
                            <div>
                                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del punto de emisión</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre del punto de emisión" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" 
                                       maxlength="30" required>
                                <span id="nombre-error" class="text-red-600 text-xs mt-1 hidden"></span>
                            </div>
                            <div>
                                <label for="cod_sucursal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
                                <select name="cod_sucursal" id="cod_sucursal" 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" 
                                        required>
                                    <option value="" disabled selected>Selecciona una sucursal</option>
                                    @forelse ($sucursales as $sucursal)
                                        <option value="{{ $sucursal['COD_SUCURSAL'] }}">{{ $sucursal['NOMBRE_SUCURSAL'] }}</option>
                                    @empty
                                        <option value="" disabled>No hay sucursales disponibles</option>
                                    @endforelse
                                </select>
                                <span id="cod_sucursal-error" class="text-red-600 text-xs mt-1 hidden"></span>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Fondo del modal con transparencia */
    #crear-punto-emision-modal {
        background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #crear-punto-emision-modal:not(.hidden) ~ #puntos-container {
        filter: blur(4px); /* Desenfoque del fondo */
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #puntos-container {
        transition: filter 0.3s ease;
    }

    /* Ocultar las flechas de los inputs tipo number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield; /* Firefox */
    }
</style>

<!-- Incluir Flowbite y el script para manejar el formulario con AJAX -->
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script>
        // Función para mostrar mensajes de error debajo de un campo específico
        function mostrarError(campoId, mensaje) {
            const errorSpan = document.getElementById(`${campoId}-error`);
            errorSpan.textContent = mensaje;
            errorSpan.classList.remove('hidden');
        }

        // Función para limpiar todos los mensajes de error
        function limpiarErrores() {
            const errorSpans = document.querySelectorAll('.text-red-600');
            errorSpans.forEach(span => {
                span.textContent = '';
                span.classList.add('hidden');
            });
        }

        // Prevenir entrada de letras en los campos de Código y Establecimiento
        document.getElementById('codigo').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Solo permite números
            if (this.value.length > 3) this.value = this.value.slice(0, 3); // Limita a 3 dígitos
        });

        document.getElementById('establecimiento').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Solo permite números
            if (this.value.length > 3) this.value = this.value.slice(0, 3); // Limita a 3 dígitos
        });

        document.getElementById('crear-punto-emision-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            // Limpiar errores previos
            limpiarErrores();

            const codigo = document.getElementById('codigo').value;
            const establecimiento = document.getElementById('establecimiento').value;
            const nombre = document.getElementById('nombre').value;
            const codSucursal = document.getElementById('cod_sucursal').value;

            // Validaciones personalizadas
            let tieneErrores = false;

            if (!/^\d{3}$/.test(codigo)) {
                mostrarError('codigo', 'El código debe ser exactamente 3 dígitos numéricos.');
                tieneErrores = true;
            }
            if (!/^\d{3}$/.test(establecimiento)) {
                mostrarError('establecimiento', 'El establecimiento debe ser exactamente 3 dígitos numéricos.');
                tieneErrores = true;
            }
            if (nombre.trim() === '' || nombre.length > 50) {
                mostrarError('nombre', 'El nombre es obligatorio y no debe exceder los 50 caracteres.');
                tieneErrores = true;
            }
            if (!codSucursal) {
                mostrarError('cod_sucursal', 'Debes seleccionar una sucursal.');
                tieneErrores = true;
            }

            if (tieneErrores) {
                return;
            }

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
                    const modal = document.getElementById('crear-punto-emision-modal');
                    modal.classList.add('hidden');
                    window.location.reload();
                } else {
                    // Mostrar errores del servidor debajo de los campos correspondientes
                    if (data.errors) {
                        for (const [campo, mensaje] of Object.entries(data.errors)) {
                            mostrarError(campo, mensaje[0]); // Mostrar el primer mensaje de error por campo
                        }
                    } else {
                        // Si no hay errores específicos, mostrar un mensaje genérico debajo del primer campo
                        mostrarError('codigo', data.error || 'Error al crear el punto de emisión');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('codigo', 'Ocurrió un error al procesar la solicitud');
            });
        });
    </script>
@endsection
@endsection
