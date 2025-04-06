@extends('layouts.app')

@section('title', 'Actualizar Cliente - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">ACTUALIZAR CLIENTE</h1>
       <form id="actualizarClienteForm" action="{{ route('clientes.actualizar', $cliente['COD_CLIENTE'] ?? '') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Campo oculto para COD_CLIENTE -->
        <input type="hidden" name="COD_CLIENTE" value="{{ $cliente['COD_CLIENTE'] ?? '' }}">

        <!-- Sección de Nombres -->
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
        <div class="w-full">
                <label for="PRIMER_NOMBRE_C" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Primer Nombre</label>
                <input type="text" id="PRIMER_NOMBRE_C" name="PRIMER_NOMBRE_C" value="{{ $cliente['PRIMER_NOMBRE_C'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Nombre" required maxlength="30" minlength="3">
            </div>
            <div class="w-full">
                <label for="SEGUNDO_NOMBRE_C" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Segundo Nombre</label>
                <input type="text" id="SEGUNDO_NOMBRE_C" name="SEGUNDO_NOMBRE_C" value="{{ $cliente['SEGUNDO_NOMBRE_C'] ?? '' }}"class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Nombre" required maxlength="30" minlength="3">
            </div>
            <div class="w-full">
                <label for="PRIMER_APELLIDO_C" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Primer Apellido</label>
                <input type="text" id="PRIMER_APELLIDO_C" name="PRIMER_APELLIDO_C" value="{{ $cliente['PRIMER_APELLIDO_C'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Apellido" required maxlength="30" minlength="3">
            </div>
                <div class="w-full">
                <label for="SEGUNDO_APELLIDO_C" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Segundo Apellido</label>
                <input type="text" id="SEGUNDO_APELLIDO_C" name="SEGUNDO_APELLIDO_C" value="{{ $cliente['SEGUNDO_APELLIDO_C'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Apellido" required maxlength="30" minlength="3">
            </div>
    
            <div class="w-full">
                <label for="NUMERO_IDENTIDAD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Número de Identidad</label>
                <input type="text" id="NUMERO_IDENTIDAD" name="NUMERO_IDENTIDAD" value="{{ $cliente['NUMERO_IDENTIDAD'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-00000" required maxlength="15" minlength="15" pattern="\d{4}-\d{4}-\d{5}" title="El formato debe ser 0000-0000-00000" oninput="formatearIdentidad(this)">
                </div>
                <div class="w-full">
                <label for="RTN" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">RTN</label>
                <input type="text" id="RTN" name="RTN" value="{{ $cliente['RTN'] ?? '' }}"class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-000000" required maxlength="16" minlength="16" pattern="\d{4}-\d{4}-\d{6}" title="El formato debe ser 0000-0000-000000" oninput="formatearRTN(this)">
            </div>
            <div class="w-full">
                <label for="NUMERO_TELEFONO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Número de Teléfono</label>
                <input type="text" id="NUMERO_TELEFONO" name="NUMERO_TELEFONO" value="{{ $cliente['NUMERO_TELEFONO'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000" required maxlength="10" minlength="10" pattern="\d{4}-\d{4}" oninput="formatearTelefono(this)">
            </div>
            <div class="w-full">
                <label for="TIPO_TELEFONO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tipo de Teléfono</label>
                <select name="TIPO_TELEFONO" id="TIPO_TELEFONO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                <option value="" disabled>Seleccionar Tipo Teléfono</option>
                <option value="PERSONAL" {{ ($cliente['TIPO_TELEFONO'] ?? '') == 'PERSONAL' ? 'selected' : '' }}>Personal</option>
                <option value="LABORAL" {{ ($cliente['TIPO_TELEFONO'] ?? '') == 'LABORAL' ? 'selected' : '' }}>Laboral</option>
                <option value="OTRO" {{ ($cliente['TIPO_TELEFONO'] ?? '') == 'OTRO' ? 'selected' : '' }}>Otro</option>
            </select>
                </div>

<div class="w-full">
                <label for="CORREO_ELECTRONICO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Correo Electrónico</label>
                <input type="email" id="CORREO_ELECTRONICO" name="CORREO_ELECTRONICO" value="{{ $cliente['CORREO_ELECTRONICO'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)" title="El correo electronico debe ser de uno de estos servicios: gmail.com, yahoo.com, outlook.com o hotmail.com" placeholder="Correo Electrónico" required maxlength="50">
                </div>
                <div class="w-full">
                <label for="TIPO_CORREO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tipo de Correo</label>
                <select name="TIPO_CORREO" id="TIPO_CORREO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="" disabled>Seleccionar Tipo Correo</option>
                    <option value="PERSONAL" {{ ($cliente['TIPO_CORREO'] ?? '') == 'PERSONAL' ? 'selected' : '' }}>Personal</option>
                    <option value="LABORAL" {{ ($cliente['TIPO_CORREO'] ?? '') == 'LABORAL' ? 'selected' : '' }}>Laboral</option>
                    <option value="OTRO" {{ ($cliente['TIPO_CORREO'] ?? '') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                </select>
                </div>

                <div class="w-full">
                <label for="CALLE" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Calle</label>
                <input type="text" id="CALLE" name="CALLE" value="{{ $cliente['CALLE'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Calle" required maxlength="60">
                </div>
                <div class="w-full">
                <label for="CIUDAD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ciudad</label>
                <input type="text" id="CIUDAD" name="CIUDAD" value="{{ $cliente['CIUDAD'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ciudad" required maxlength="30">
                </div>
                <div class="w-full">
                <label for="PAIS" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">País</label>
                <input type="text" id="PAIS" name="PAIS" value="{{ $cliente['PAIS'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="País" required maxlength="20">
                </div>
                <div class="w-full">
                <label for="CODIGO_POSTAL" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Código Postal</label>
                <input type="text" id="CODIGO_POSTAL" name="CODIGO_POSTAL" value="{{ $cliente['CODIGO_POSTAL'] ?? '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código Postal" required maxlength="7">
                </div>
                <div class="w-full">
                <label for="TIPO_DIRECCION" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tipo de Dirección</label>
                <select name="TIPO_DIRECCION" id="TIPO_DIRECCION" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                    <option value="" disabled>Seleccionar Tipo Dirección</option>
                    <option value="DOMICILIO" {{ ($cliente['TIPO_DIRECCION'] ?? '') == 'DOMICILIO' ? 'selected' : '' }}>Domicilio</option>
                    <option value="LABORAL" {{ ($cliente['TIPO_DIRECCION'] ?? '') == 'LABORAL' ? 'selected' : '' }}>Laboral</option>
                    <option value="OTRO" {{ ($cliente['TIPO_DIRECCION'] ?? '') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                </select>
                </div>
</div>

        <!-- Botones -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('clientes.listar') }}" class="text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</a>
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">Actualizar Cliente</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre del contacto
        document.getElementById('PRIMER_NOMBRE_C').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de apellido del contacto
        document.getElementById('SEGUNDO_NOMBRE_C').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
        // Validación para el campo de apellido del contacto
        document.getElementById('PRIMER_APELLIDO_C').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
        
        // Validación para el campo de apellido del contacto
        document.getElementById('SEGUNDO_APELLIDO_C').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de número de identidad
        document.getElementById('NUMERO_IDENTIDAD').addEventListener('input', function (event) {
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
        document.getElementById('RTN').addEventListener('input', function (event) {
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
          document.getElementById('NUMERO_TELEFONO').addEventListener('input', function (event) {
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
        document.getElementById('CALLE').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de ciudad
        document.getElementById('CIUDAD').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de país
        document.getElementById('PAIS').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de código postal
        document.getElementById('CODIGO_POSTAL').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });


        // Validación para el campo de correo_electronico electrónico
        document.getElementById('CORREO_ELECTRONICO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
        });

        document.getElementById("actualizarClienteForm").addEventListener("submit", function (event) {
            const CORREO_ELECTRONICO = document.getElementById("CORREO_ELECTRONICO").value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)$/;

            if (!emailRegex.test(CORREO_ELECTRONICO)) {
                event.preventDefault();
                alert("El correo electrónico debe terminar con uno de los siguientes dominios: gmail.com, yahoo.com, outlook.com o hotmail.com o .hn");
                document.getElementById("CORREO_ELECTRONICO").focus();
            }
        });
        
        // Validación para el campo de apellido del contacto
        document.getElementById('TIPO_CORREO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

         // Validación para el campo de apellido del contacto
         document.getElementById('TIPO_DIRECCION').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

         // Validación para el campo de apellido del contacto
        document.getElementById('TIPO_TELEFONO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
    });

</script>
@endsection
