@extends('layouts.app')

@section('title', 'Crear Empleado - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
    <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
        <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR EMPLEADO</h1>
        <form id="crearEmpleadoForm" action="{{ route('empleados.insertar') }}" method="POST">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <!-- Primer Nombre -->
                <div class="w-full">
                    <label for="primer_nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Primer Nombre</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Nombre" required maxlength="30" minlength="3">
                </div>

                <!-- Segundo Nombre -->
                <div class="w-full">
                    <label for="segundo_nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Nombre" required maxlength="30" minlength="3">
                </div>

                <!-- Primer Apellido -->
                <div class="w-full">
                    <label for="primer_apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Primer Apellido</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Apellido" required maxlength="30" minlength="3">
                </div>

                <!-- Segundo Apellido -->
                <div class="w-full">
                    <label for="segundo_apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Apellido" required maxlength="30" minlength="3">
                </div>

                <!-- Número de Identidad -->
                <div class="w-full">
                    <label for="numero_identidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Identidad</label>
                    <input type="text" id="numero_identidad" name="numero_identidad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-00000" required maxlength="16" minlength="13" pattern="\d{4}-\d{4}-\d{5}" title="El formato debe ser 0000-0000-00000" oninput="formatearIdentidad(this)">
                </div>

                <!-- RTN -->
                <div class="w-full">
                    <label for="rtn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RTN</label>
                    <input type="text" id="rtn" name="rtn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-000000" required maxlength="16" minlength="14" pattern="\d{4}-\d{4}-\d{6}" title="El formato debe ser 0000-0000-000000" oninput="formatearRTN(this)">
                </div>

                <!-- Puesto -->
                <div class="w-full">
                    <label for="puesto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Puesto</label>
                    <select name="puesto" id="puesto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar el Puesto</option>
                        <option value="GERENTE">Gerente</option>
                        <option value="ADMINISTRADOR">Administrador</option>
                        <option value="VENDEDOR">Vendedor</option>
                        <option value="JEFE PRODUCCION">Jefe de Producción</option>
                        <option value="JEFE DE VENTAS">Jefe de Ventas</option>
                    </select>
                </div>

                <!-- Número de Teléfono -->
                <div class="w-full">
                    <label for="numero_telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                    <input type="text" name="numero_telefono" id="numero_telefono" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000" required maxlength="15" minlength="8" pattern="\d{4}-\d{4}" oninput="formatearTelefono(this)">
                </div>

                <!-- Tipo de Teléfono -->
                <div class="w-full">
                    <label for="tipo_telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Teléfono</label>
                    <select name="tipo_telefono" id="tipo_telefono" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar Tipo de Teléfono</option>
                        <option value="PERSONAL">Personal</option>
                        <option value="LABORAL">Laboral</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <!-- Correo Electrónico -->
                <div class="w-full">
                    <label for="correo_electronico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                    <input type="email" name="correo_electronico" id="correo_electronico" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)" title="El correo electrónico debe ser de uno de estos servicios: gmail.com, yahoo.com, outlook.com, hotmail.com o .hn" placeholder="Correo Electrónico" required maxlength="30">
                </div>

                <!-- Tipo de Correo -->
                <div class="w-full">
                    <label for="tipo_correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Correo</label>
                    <select name="tipo_correo" id="tipo_correo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar Tipo de Correo</option>
                        <option value="PERSONAL">Personal</option>
                        <option value="LABORAL">Laboral</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>

                <!-- Calle -->
                <div class="w-full">
                    <label for="calle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Calle</label>
                    <input type="text" name="calle" id="calle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Calle" required maxlength="50">
                </div>

                <!-- Ciudad -->
                <div class="w-full">
                    <label for="ciudad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ciudad" required maxlength="50">
                </div>

                <!-- País -->
                <div class="w-full">
                    <label for="pais" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">País</label>
                    <input type="text" name="pais" id="pais" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="País" required maxlength="50">
                </div>

                <!-- Código Postal -->
                <div class="w-full">
                    <label for="codigo_postal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Postal</label>
                    <input type="text" name="codigo_postal" id="codigo_postal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código Postal" required maxlength="50">
                </div>

                <!-- Tipo de Dirección -->
                <div class="w-full">
                    <label for="tipo_direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Dirección</label>
                    <select name="tipo_direccion" id="tipo_direccion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                        <option value="" selected disabled>Seleccionar Tipo de Dirección</option>
                        <option value="DOMICILIO">Domicilio</option>
                        <option value="LABORAL">Laboral</option>
                        <option value="OTRO">Otro</option>
                    </select>
                </div>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
                Crear Empleado
            </button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre del contacto
        document.getElementById('primer_nombre').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de apellido del contacto
        document.getElementById('segundo_nombre').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de apellido del contacto
        document.getElementById('primer_apellido').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de apellido del contacto
        document.getElementById('segundo_apellido').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de número de identidad
        document.getElementById('numero_identidad').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            formatearIdentidad(this);
        });

        // Función para formatear el número de identidad
        function formatearIdentidad(input) {
            let valor = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
            if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
            if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 14);
            input.value = valor;
        }

        // Validación para el campo de rtn
        document.getElementById('rtn').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            formatearRTN(this);
        });

        // Función para formatear el rtn
        function formatearRTN(input) {
            let valor = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
            if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
            if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 15);
            input.value = valor;
        }

        // Validación para el campo de teléfono
        document.getElementById('numero_telefono').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            formatearTelefono(this);
        });

        // Función para formatear el teléfono
        function formatearTelefono(input) {
            let valor = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
            if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4, 8);
            input.value = valor;
        }

        // Validación para el campo de calle
        document.getElementById('calle').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de ciudad
        document.getElementById('ciudad').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de país
        document.getElementById('pais').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de código postal
        document.getElementById('codigo_postal').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });

        // Validación para el campo de correo electrónico
        document.getElementById('correo_electronico').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
        });

        // Validación para el campo de tipo de correo
        document.getElementById('tipo_correo').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de tipo de dirección
        document.getElementById('tipo_direccion').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
 // Validación para el campo de tipo de dirección
 document.getElementById('tipo_telefono').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
        
        // Validación del formulario antes de enviar
        document.getElementById('crearEmpleadoForm').addEventListener('submit', function (event) {
            const correo_electronico = document.getElementById('correo_electronico').value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)$/;

            if (!emailRegex.test(correo_electronico)) {
                event.preventDefault();
                alert('El correo electrónico debe terminar con uno de los siguientes dominios: gmail.com, yahoo.com, outlook.com, hotmail.com o .hn');
                document.getElementById('correo_electronico').focus();
            }
        });
    });
</script>
@endsection
