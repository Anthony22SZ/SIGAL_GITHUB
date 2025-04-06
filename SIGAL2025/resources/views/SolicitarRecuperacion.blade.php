<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="mb-1 text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Recuperar Contraseña
        </h2>
        <form class="mt-4 space-y-4 lg:mt-5 md:space-y-5" id="recuperacionForm">
            <div>
                <label for="correo_electronico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo Electrónico</label>
                <input type="email" name="correo_electronico" id="correo_electronico" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
            </div>
            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Solicitar Recuperación</button>
        </form>
        <div class="message text-green-500 mt-4" id="message"></div>
        <div class="error text-red-500 mt-4" id="error"></div>
    </div>

<script>
    document.getElementById('recuperacionForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const correo_electronico = document.getElementById('correo_electronico').value;
        const messageDiv = document.getElementById('message');
        const errorDiv = document.getElementById('error');

        try {
            const response = await fetch('/solicitar-recuperacion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF Token
                },
                body: JSON.stringify({ correo_electronico })
            });

            const data = await response.json();
            if (response.ok) {
                messageDiv.textContent = data.message;
                errorDiv.textContent = '';
                // Redirigir a la pantalla de verificación de código
                window.location.href = '/verificar-codigo';
            } else {
                errorDiv.textContent = data.message;
                messageDiv.textContent = '';
            }
        } catch (error) {
            errorDiv.textContent = 'Error al solicitar recuperación de contraseña';
            messageDiv.textContent = '';
        }
    });
</script>
</body>
</html>
