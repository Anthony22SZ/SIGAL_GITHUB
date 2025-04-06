@extends('layouts.app')

@section('title', 'Configuración Fiscal - Datos de Empresa - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <!-- Título y descripción -->
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">CONFIGURACIÓN FISCAL</h1>
            <p class="text-gray-500 dark:text-gray-400">Gestiona la configuración fiscal y CAI para Honduras</p>
        </div>

        <!-- Mensajes de éxito o error -->
        @if (session('success'))
            <div id="success-message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="error-message" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pestañas de navegación -->
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li class="me-2">
                    <a href="{{ route('cai.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">CAI</a>
                </li>
                <li class="me-2">
                    <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500">Datos de Empresa</a>
                </li>
                <li class="me-2">
                    <a href="{{ route('puntos-emision.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Puntos de Emisión</a>
                </li>
                <li class="me-2">
                    <a href="{{ route('tipos-documento.listar') }}" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Tipos de Documento</a>
                </li>
            </ul>
        </div>

        <!-- Sección de Datos de Empresa -->
        <div class="mt-6">
            <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Datos de Empresa</h2>
            <p class="text-gray-600 mb-4">Configura la información fiscal de tu empresa</p>

            <form id="empresaForm" action="{{ route('empresa.actualizar', $empresa['COD_EMPRESA'] ?? 1) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Información General -->
                <div>
                    <h3 class="text-lg font-medium mb-2 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información General
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="razon_social" class="block mb-2 text-sm font-medium text-gray-700">Razón Social</label>
                            <input type="text" id="razon_social" name="razon_social" value="{{ old('razon_social', $empresa['RAZON_SOCIAL'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('razon_social') border-red-500 @enderror" placeholder="Razón Social" required maxlength="50" minlength="3">
                            @error('razon_social')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nombre_comercial" class="block mb-2 text-sm font-medium text-gray-700">Nombre Comercial</label>
                            <input type="text" id="nombre_comercial" name="nombre_comercial" value="{{ old('nombre_comercial', $empresa['NOMBRE_COMERCIAL'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('nombre_comercial') border-red-500 @enderror" placeholder="Nombre Comercial" required maxlength="20" minlength="3">
                            @error('nombre_comercial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="rtn" class="block mb-2 text-sm font-medium text-gray-700">RTN</label>
                            <input type="text" id="rtn" name="rtn" value="{{ old('rtn', $empresa['RTN'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('rtn') border-red-500 @enderror" placeholder="0000-0000-000000" required maxlength="20" minlength="14" pattern="\d{4}-\d{4}-\d{6}" title="El formato debe ser 0000-0000-000000" oninput="formatearRTN(this)">
                            @error('rtn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="regimen_fiscal" class="block mb-2 text-sm font-medium text-gray-700">Régimen Fiscal</label>
                            <input type="text" id="regimen_fiscal" name="regimen_fiscal" value="{{ old('regimen_fiscal', $empresa['REGIMEN_FISCAL'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('regimen_fiscal') border-red-500 @enderror" placeholder="Régimen Fiscal" required maxlength="50" minlength="3">
                            @error('regimen_fiscal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="actividad_economica" class="block mb-2 text-sm font-medium text-gray-700">Actividad Económica</label>
                            <input type="text" id="actividad_economica" name="actividad_economica" value="{{ old('actividad_economica', $empresa['ACTIVIDAD_ECONOMICA'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('actividad_economica') border-red-500 @enderror" placeholder="Actividad Económica" required maxlength="50" minlength="3">
                            @error('actividad_economica')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Logo de la Empresa -->
                <div>
                    <h3 class="text-lg font-medium mb-2 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4m0 0l4 4m-4-4v12m8-8h-4m4 0h4m-4-4v8"></path>
                        </svg>
                        Logo de la Empresa
                    </h3>
                    <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center">
                        @if ($empresa['LOGO'])
                            <img src="{{ $empresa['LOGO'] }}" alt="Logo de la empresa" class="mx-auto h-20 w-auto mb-2">
                        @else
                            <p class="text-gray-700">Logo de la empresa</p>
                        @endif
                        <input type="file" id="logo" name="logo" accept="image/*" class="mt-2 text-gray-700 block w-full @error('logo') border-red-500 @enderror" title="Formatos: JPG, PNG. Máximo: 2MB">
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Seleccionar archivo <span class="text-gray-400">Sin archivos seleccionados</span></p>
                        <p class="text-xs text-gray-500 mt-1">Formatos recomendados: PNG, JPG. Tamaño máximo: 2MB</p>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium mb-2 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Dirección
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="direccion" class="block mb-2 text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $empresa['DIRECCION'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('direccion') border-red-500 @enderror" placeholder="Dirección" required maxlength="50">
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="ciudad" class="block mb-2 text-sm font-medium text-gray-700">Ciudad</label>
                                <input type="text" id="ciudad" name="ciudad" value="{{ old('ciudad', $empresa['CIUDAD'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('ciudad') border-red-500 @enderror" placeholder="Ciudad" required maxlength="50">
                                @error('ciudad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="departamento" class="block mb-2 text-sm font-medium text-gray-700">Departamento</label>
                                <input type="text" id="departamento" name="departamento" value="{{ old('departamento', $empresa['DEPARTAMENTO'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('departamento') border-red-500 @enderror" placeholder="Departamento" required maxlength="50">
                                @error('departamento')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium mb-2 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Contacto
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="telefono" class="block mb-2 text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $empresa['TELEFONO'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('telefono') border-red-500 @enderror" placeholder="0000-0000" required maxlength="15" minlength="8" pattern="\d{4}-\d{4}" oninput="formatearTelefono(this)">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $empresa['EMAIL'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('email') border-red-500 @enderror" placeholder="Correo Electrónico" required maxlength="30" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)" title="El correo debe ser de gmail.com, yahoo.com, outlook.com, hotmail.com o .hn">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="sitio_web" class="block mb-2 text-sm font-medium text-gray-700">Sitio Web</label>
                        <input type="url" id="sitio_web" name="sitio_web" value="{{ old('sitio_web', $empresa['SITIO_WEB'] ?? '') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 @error('sitio_web') border-red-500 @enderror" placeholder="https://example.com" maxlength="50">
                        @error('sitio_web')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botón Guardar Cambios -->
                <div class="col-span-2 flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-md flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript para validaciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Validación para razón social
            document.getElementById('razon_social').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para nombre comercial
            document.getElementById('nombre_comercial').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para RTN
            document.getElementById('rtn').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^0-9-]/g, '');
                formatearRTN(this);
            });

            // Función para formatear RTN
            function formatearRTN(input) {
                let valor = input.value.replace(/\D/g, '');
                if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
                if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 15);
                input.value = valor;
            }

            // Validación para régimen fiscal
            document.getElementById('regimen_fiscal').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para actividad económica
            document.getElementById('actividad_economica').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para dirección
            document.getElementById('direccion').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para ciudad
            document.getElementById('ciudad').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para departamento
            document.getElementById('departamento').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
            });

            // Validación para teléfono
            document.getElementById('telefono').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^0-9-]/g, '');
                formatearTelefono(this);
            });

            // Función para formatear teléfono
            function formatearTelefono(input) {
                let valor = input.value.replace(/\D/g, '');
                if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4, 8);
                input.value = valor;
            }

            // Validación para email
            document.getElementById('email').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
            });

            // Validación para sitio web
            document.getElementById('sitio_web').addEventListener('input', function (event) {
                this.value = this.value.replace(/[^a-zA-Z0-9.:/-]/g, '');
            });

            // Validación del formulario antes de enviar
            document.getElementById('empresaForm').addEventListener('submit', function (event) {
                const email = document.getElementById('email').value;
                const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)$/;

                if (!emailRegex.test(email)) {
                    event.preventDefault();
                    alert('El correo electrónico debe terminar con uno de los siguientes dominios: gmail.com, yahoo.com, outlook.com, hotmail.com o .hn');
                    document.getElementById('email').focus();
                }
            });

            // Ocultar mensaje de éxito después de 5 segundos
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.remove();
                    }, 500);
                }, 5000);
            }

            // Ocultar mensaje de error después de 5 segundos
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.transition = 'opacity 0.5s ease';
                    errorMessage.style.opacity = '0';
                    setTimeout(() => {
                        errorMessage.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>
</section>
@endsection