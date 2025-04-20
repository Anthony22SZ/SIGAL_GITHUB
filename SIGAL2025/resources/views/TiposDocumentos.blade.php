@extends('layouts.app')

@section('title', 'Tipos de Documento')

@section('content')
<div id="documentos-container" class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        <a href="{{ route('puntos-emision.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Puntos de Emisión</a>
                    </li>
                    <li class="me-2">
                        <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Tipos de Documento</a>
                    </li>
                </ul>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tipos Documentos</h2>
                    <!-- Botón para abrir el modal -->
                    <button data-modal-target="crear-tipo-documento-modal" data-modal-toggle="crear-tipo-documento-modal" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Tipo de Documento
                    </button>
                </div>
                <p class="text-gray-600 mb-4">Configura los tipos de documentos fiscales que emite tu empresa</p>

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

                <!-- Tabla de tipos de documento -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Código</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Descripción</th>
                                <th scope="col" class="px-6 py-3">Afecta Inventario</th>
                                <th scope="col" class="px-6 py-3">Requiere Cliente</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposDocumento as $tipo)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $tipo['CODIGO'] }}</td>
                                    <td class="px-6 py-4">{{ $tipo['NOMBRE'] }}</td>
                                    <td class="px-6 py-4">{{ $tipo['DESCRIPCION'] ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $tipo['AFECTA_INVENTARIO'] ? 'Sí' : 'No' }}</td>
                                    <td class="px-6 py-4">{{ $tipo['REQUIERE_CLIENTE'] ? 'Sí' : 'No' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                            {{ $tipo['ESTADO'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center space-x-2">
                                            <div>
                                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline flex items-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline flex items-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0V3a1 1 0 011-1h2a1 1 0 011 1v1m-7 0h10"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay tipos de documento disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal para Crear Tipo de Documento -->
                <div id="crear-tipo-documento-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-900 bg-opacity-50">
                    <div class="relative p-4 w-full max-w-lg max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Nuevo Tipo de Documento
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="crear-tipo-documento-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Cerrar modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form id="crear-tipo-documento-form" action="{{ route('tipos-documento.crear') }}" method="POST">
                                @csrf
                                <div class="p-4 md:p-5 space-y-4">
                                    <p class="text-sm text-gray-600">Ingresa la información del nuevo tipo de documento</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="codigo" class="block mb-2 text-sm font-medium text-gray-900">Código</label>
                                            <input type="number" name="codigo" id="codigo" placeholder="Código de 2 dígitos" 
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                                   min="0" max="99" step="1" maxlength="2" oninput="this.value = this.value.slice(0, 2)" required>
                                            <span id="codigo-error" class="text-red-600 text-xs mt-1 hidden"></span>
                                        </div>
                                        <div>
                                            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900">Nombre</label>
                                            <input type="text" name="nombre" id="nombre" placeholder="Nombre del tipo de documento" 
                                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                                   maxlength="30" required>
                                            <span id="nombre-error" class="text-red-600 text-xs mt-1 hidden"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                                        <textarea name="descripcion" id="descripcion" placeholder="Descripción del tipo de documento" 
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                                  maxlength="50"></textarea>
                                        <span id="descripcion-error" class="text-red-600 text-xs mt-1 hidden"></span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="afecta_inventario" class="block mb-2 text-sm font-medium text-gray-900">Afecta Inventario</label>
                                            <input type="checkbox" name="afecta_inventario" id="afecta_inventario" value="1" 
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                        </div>
                                        <div>
                                            <label for="requiere_cliente" class="block mb-2 text-sm font-medium text-gray-900">Requiere Cliente</label>
                                            <input type="checkbox" name="requiere_cliente" id="requiere_cliente" value="1" checked 
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
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
            </div>
        </div>
    </section>
</div>

<style>
    /* Fondo del modal con transparencia */
    #crear-tipo-documento-modal {
        background: rgba(0, 0, 0, 0.5); /* Mantener la opacidad original */
    }

    /* Efecto de desenfoque en el contenedor principal cuando el modal está visible */
    #crear-tipo-documento-modal:not(.hidden) ~ #documentos-container {
        filter: blur(4px);
        transition: filter 0.3s ease;
    }

    /* Restaurar el estado normal cuando el modal está oculto */
    #documentos-container {
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
@endsection

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

        // Prevenir entrada de letras en el campo Código
        document.getElementById('codigo').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, ''); // Solo permite números
            if (this.value.length > 2) this.value = this.value.slice(0, 2); // Limita a 2 dígitos
        });

        document.getElementById('crear-tipo-documento-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            // Limpiar errores previos
            limpiarErrores();

            const codigo = document.getElementById('codigo').value;
            const nombre = document.getElementById('nombre').value;
            const descripcion = document.getElementById('descripcion').value;

            // Validaciones personalizadas
            let tieneErrores = false;

            if (!/^\d{2}$/.test(codigo)) {
                mostrarError('codigo', 'El código debe ser exactamente 2 dígitos numéricos.');
                tieneErrores = true;
            }
            if (nombre.trim() === '' || nombre.length > 50) {
                mostrarError('nombre', 'El nombre es obligatorio y no debe exceder los 50 caracteres.');
                tieneErrores = true;
            }
            if (descripcion.length > 200) {
                mostrarError('descripcion', 'La descripción no debe exceder los 200 caracteres.');
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
                    const modal = document.getElementById('crear-tipo-documento-modal');
                    modal.classList.add('hidden');
                    window.location.reload();
                } else {
                    // Mostrar errores del servidor debajo de los campos correspondientes
                    if (data.errors) {
                        for (const [campo, mensajes] of Object.entries(data.errors)) {
                            mostrarError(campo, mensajes[0]); // Mostrar el primer mensaje de error por campo
                        }
                    } else {
                        // Si no hay errores específicos, mostrar un mensaje genérico debajo del primer campo
                        mostrarError('codigo', data.message || 'Error al crear el tipo de documento');
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
