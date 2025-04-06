
@extends('layouts.app')

@section('title', 'Crear Proveedor - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Crear Proveedor</h1>
      <form id="crearProveedorForm" action="{{ route('proveedores.insertar') }}" method="POST">
          @csrf
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                  <label for="NOMBRE_PROVEEDOR" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Proveedor</label>
                  <input type="text" name="NOMBRE_PROVEEDOR" id="NOMBRE_PROVEEDOR" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre del proveedor" required maxlength="40" minlength="3">
              </div>
              <div class="w-full">
                  <label for="NOMBRE_CONTACTO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Contacto</label>
                  <input type="text" name="NOMBRE_CONTACTO" id="NOMBRE_CONTACTO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nombre del contacto" required maxlength="30"minlength="3">
              </div>
              <div class="w-full">
                  <label for="APELLIDO_CONTACTO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellido del Contacto</label>
                  <input type="text" name="APELLIDO_CONTACTO" id="APELLIDO_CONTACTO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Apellido del contacto" required maxlength="30" minlength="3">
              </div>
              <div class="w-full">
                  <label for="NUMERO_IDENTIDAD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Identidad</label>
                  <input type="text" id="NUMERO_IDENTIDAD" name="NUMERO_IDENTIDAD" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-00000" required maxlength="15" minlength="15" pattern="\d{4}-\d{4}-\d{5}" title="El formato debe ser 0000-0000-00000" oninput="formatearIdentidad(this)">
              </div>
              <div class="w-full">
                  <label for="RTN" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RTN</label>
                  <input type="text" id="RTN" name="RTN" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-000000" required maxlength="16" minlength="16" pattern="\d{4}-\d{4}-\d{6}" title="El formato debe ser 0000-0000-000000" oninput="formatearRTN(this)">
              </div>
              <div class="w-full">
                  <label for="CALLE" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Calle</label>
                  <input type="text" name="CALLE" id="CALLE" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Calle" required maxlength="60">
              </div>
              <div class="w-full">
                  <label for="CIUDAD" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciudad</label>
                  <input type="text" name="CIUDAD" id="CIUDAD" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ciudad" required maxlength="30">
              </div>
              <div class="w-full">
                  <label for="PAIS" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">País</label>
                  <input type="text" name="PAIS" id="PAIS" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="País" required maxlength="20">
              </div>
              <div class="w-full">
                  <label for="CODIGO_POSTAL" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Postal</label>
                  <input type="text" name="CODIGO_POSTAL" id="CODIGO_POSTAL" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código Postal" required maxlength="7">
              </div>
              <div class="w-full">
                  <label for="TELEFONO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                  <input type="text" name="TELEFONO" id="TELEFONO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000" required maxlength="10" minlength="10" pattern="\d{4}-\d{4}" oninput="formatearTelefono(this)">
              </div>
              <div class="w-full">
                  <label for="CORREO" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                  <input type="email" name="CORREO" id="CORREO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)" title="El correo debe ser de uno de estos servicios: gmail.com, yahoo.com, outlook.com o hotmail.com" placeholder="Correo electrónico" required maxlength="50">
              </div>
          </div>
          <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
              Crear Proveedor
          </button>
      </form>
  </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validación para el campo de nombre del proveedor
        document.getElementById('NOMBRE_PROVEEDOR').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de nombre del contacto
        document.getElementById('NOMBRE_CONTACTO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });

        // Validación para el campo de apellido del contacto
        document.getElementById('APELLIDO_CONTACTO').addEventListener('input', function (event) {
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

        // Validación para el campo de RTN
        document.getElementById('RTN').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            formatearRTN(this);
        });

        // Función para formatear el RTN
        function formatearRTN(input) {
            let valor = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
            if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
            if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 15);
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

        // Validación para el campo de teléfono
        document.getElementById('TELEFONO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            formatearTelefono(this);
            
        });

       // Función para formatear el teléfono
       function formatearTelefono(input) {
            let valor = input.value.replace(/\D/g, ''); // Elimina todo lo que no sea número
            if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4, 8);
            input.value = valor;
        }

        // Validación para el campo de correo electrónico
        document.getElementById('CORREO').addEventListener('input', function (event) {
            this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
        });

        document.getElementById("crearProveedorForm").addEventListener("submit", function (event) {
            const correo = document.getElementById("CORREO").value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)$/;

            if (!emailRegex.test(correo)) {
                event.preventDefault();
                alert("El correo electrónico debe terminar con uno de los siguientes dominios: gmail.com, yahoo.com, outlook.com o hotmail.com o .hn");
                document.getElementById("CORREO").focus();
            }
        });
    });

 
</script>
@endsection