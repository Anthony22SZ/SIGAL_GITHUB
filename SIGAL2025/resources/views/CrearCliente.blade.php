@extends('layouts.app')

@section('title', 'Crear Cliente - SIGAL')

@section('content')
<section class="bg-white dark:bg-gray-900">
  <div class="py-4 px-4 mx-auto max-w-7xl lg:py-8">
      <h1 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">CREAR CLIENTE</h1>
      <form id="crearClienteForm" action="{{ route('clientes.insertar') }}" method="POST">
          @csrf
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <!-- Campos existentes (sin cambios) -->
              <div class="w-full">
                  <label for="primer_nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Primer Nombre </label>
                  <input type="text" name="primer_nombre" id="primer_nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Nombre" required maxlength="30" minlength="3">
              </div>
              <div class="w-full">
                  <label for="segundo_nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Segundo Nombre</label>
                  <input type="text" name="segundo_nombre" id="segundo_nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Nombre" required maxlength="30" minlength="3">
              </div>
              <div class="w-full">
                  <label for="primer_apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Primer Apellido</label>
                  <input type="text" name="primer_apellido" id="primer_apellido" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Primer Apellido" required maxlength="30" minlength="3">
              </div>
              <div class="w-full">
                  <label for="segundo_apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Segundo Apellido</label>
                  <input type="text" name="segundo_apellido" id="segundo_apellido" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Segundo Apellido" required maxlength="30" minlength="3">
              </div>
              <!-- Campo de número de identidad con validación ajustada -->
              <div class="w-full">
                  <label for="numero_identidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Identidad</label>
                  <input type="text" id="numero_identidad" name="numero_identidad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0801-YYYY-XXXXX" required maxlength="15" minlength="15" pattern="0801-\d{4}-\d{5}" title="El formato debe ser 0801-YYYY-XXXXX (ej. 0801-1990-01234)" oninput="formatearIdentidad(this)">
              </div>
              <!-- Resto de los campos sin cambios -->
              <div class="w-full">
                  <label for="rtn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RTN</label>
                  <input type="text" id="rtn" name="rtn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000-000000" required maxlength="16" minlength="16" pattern="\d{4}-\d{4}-\d{6}" title="El formato debe ser 0000-0000-000000" oninput="formatearRTN(this)">
              </div>
              <div class="w-full">
                  <label for="numero_telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
                  <input type="text" name="numero_telefono" id="numero_telefono" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0000-0000" required maxlength="10" minlength="10" pattern="\d{4}-\d{4}" oninput="formatearTelefono(this)">
              </div>
              <div class="w-full">
                  <label for="tipo_telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Teléfono</label>
                  <select name="tipo_telefono" id="tipo_telefono" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>   
                      <option value="" selected disabled>Seleccionar Tipo Teléfono</option>
                      <option value="PERSONAL">Personal</option>
                      <option value="LABORAL">Laboral</option>
                      <option value="OTRO">Otro</option>
                  </select>
              </div>
              <div class="w-full">
                  <label for="correo_electronico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                  <input type="email" name="correo_electronico" id="correo_electronico" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)" title="El correo electrónico debe ser de uno de estos servicios: gmail.com, yahoo.com, outlook.com, hotmail.com o .hn" placeholder="Correo Electrónico" required maxlength="50">
              </div>
              <div class="w-full">
                  <label for="tipo_correo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Correo</label>
                  <select name="tipo_correo" id="tipo_correo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>   
                      <option value="" selected disabled>Seleccionar Tipo Correo</option>
                      <option value="PERSONAL">Personal</option>
                      <option value="LABORAL">Laboral</option>
                      <option value="OTRO">Otro</option>
                  </select>
              </div>
              <div class="w-full">
                  <label for="calle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Calle</label>
                  <input type="text" name="calle" id="calle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Calle" required maxlength="60">
              </div>
              <div class="w-full">
                  <label for="ciudad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciudad</label>
                  <input type="text" name="ciudad" id="ciudad" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Ciudad" required maxlength="30">
              </div>
              <div class="w-full">
                  <label for="pais" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">País</label>
                  <input type="text" name="pais" id="pais" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="País" required maxlength="20">
              </div>
              <div class="w-full">
                  <label for="codigo_postal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código Postal</label>
                  <input type="text" name="codigo_postal" id="codigo_postal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Código Postal" required maxlength="7">
              </div>
              <div class="w-full">
                  <label for="tipo_direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo Dirección</label>
                  <select name="tipo_direccion" id="tipo_direccion" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>    
                      <option value="" selected disabled>Seleccionar Tipo Dirección</option>
                      <option value="DOMICILIO">Domicilio</option>
                      <option value="LABORAL">Laboral</option>
                      <option value="OTRO">Otro</option>
                  </select>
              </div>
          </div>
          <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 hover:bg-blue-700">
              Crear Cliente
          </button>
      </form>
  </div>
</section><script>
document.addEventListener('DOMContentLoaded', function () {
    // Validaciones de nombres y apellidos
    const camposTexto = ['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'];
    camposTexto.forEach(id => {
        document.getElementById(id).addEventListener('input', function () {
            this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
        });
    });

    // Número de identidad
    const numeroIdentidad = document.getElementById('numero_identidad');
    numeroIdentidad.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9-]/g, '');
        formatearIdentidad(this);
    });

    function formatearIdentidad(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
        if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 14);
        input.value = valor.substring(0, 15);

        const anioActual = new Date().getFullYear();
        const partes = input.value.split('-');
        if (partes.length === 3) {
            const [prefijo, anio, secuencial] = partes;
            
            if (prefijo !== '0801') {
                input.setCustomValidity('El número de identidad debe comenzar con 0801');
            } else if (isNaN(anio) || anio < 1900 || anio > anioActual) {
                input.setCustomValidity(`El año debe estar entre 1900 y ${anioActual}`);
            } else if (secuencial.length !== 5 || isNaN(secuencial)) {
                input.setCustomValidity('El número secuencial debe ser de 5 dígitos');
            } else {
                input.setCustomValidity('');
            }
        } else {
            input.setCustomValidity('Formato inválido. Use 0801-YYYY-XXXXX');
        }
    }

    // RTN (Honduras: 14 dígitos + 2 guiones = 16 caracteres)
    const rtn = document.getElementById('rtn');
    rtn.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9-]/g, '');
        formatearRTN(this);
    });

    function formatearRTN(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4);
        if (valor.length > 9) valor = valor.substring(0, 9) + '-' + valor.substring(9, 15);
        input.value = valor.substring(0, 16);

        const partes = input.value.split('-');
        if (partes.length === 3 && partes[0].length === 4 && partes[1].length === 4 && partes[2].length === 6) {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('El RTN debe tener el formato XXXX-XXXX-XXXXXX con 14 dígitos');
        }
    }

    // Teléfono
    const telefono = document.getElementById('numero_telefono');
    telefono.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9-]/g, '');
        formatearTelefono(this);
    });

    function formatearTelefono(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor.length > 4) valor = valor.substring(0, 4) + '-' + valor.substring(4, 8);
        input.value = valor.substring(0, 9);
    }

    // Otros campos
    document.getElementById('calle').addEventListener('input', function () {
        this.value = this.value.replace(/[^A-Za-z0-9ñÑáéíóúÁÉÍÓÚ\s]/g, '');
    });
    document.getElementById('ciudad').addEventListener('input', function () {
        this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
    });
    document.getElementById('pais').addEventListener('input', function () {
        this.value = this.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
    });
    document.getElementById('codigo_postal').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9-]/g, '');
    });

    // Correo electrónico
    const correo = document.getElementById('correo_electronico');
    correo.addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
    });

    // Validación del formulario al enviar
    document.getElementById('crearClienteForm').addEventListener('submit', function (event) {
        // Validación del correo
        const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|hn)$/;
        if (!emailRegex.test(correo.value)) {
            event.preventDefault();
            alert('El correo debe ser de dominio gmail.com, yahoo.com, outlook.com, hotmail.com o .hn');
            correo.focus();
            return;
        }

        // Validación del número de identidad
        const partesIdentidad = numeroIdentidad.value.split('-');
        const anioActual = new Date().getFullYear();
        if (partesIdentidad.length !== 3 || partesIdentidad[0] !== '0801' || 
            isNaN(partesIdentidad[1]) || partesIdentidad[1] < 1900 || partesIdentidad[1] > anioActual || 
            partesIdentidad[2].length !== 5 || isNaN(partesIdentidad[2])) {
            event.preventDefault();
            alert('El número de identidad debe tener el formato 0801-YYYY-XXXXX válido');
            numeroIdentidad.focus();
            return;
        }

        // Validación del RTN
        const rtnRegex = /^\d{4}-\d{4}-\d{6}$/;
        if (!rtnRegex.test(rtn.value)) {
            event.preventDefault();
            alert('El RTN debe tener 14 dígitos en el formato XXXX-XXXX-XXXXXX');
            rtn.focus();
            return;
        }

        // Validación del teléfono
        const telefonoRegex = /^\d{4}-\d{4}$/;
        if (!telefonoRegex.test(telefono.value)) {
            event.preventDefault();
            alert('El teléfono debe tener el formato XXXX-XXXX');
            telefono.focus();
            return;
        }
    });
});
</script>
@endsection