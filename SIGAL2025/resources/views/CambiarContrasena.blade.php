<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen p-6">
        <div class="p-6 bg-white rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 text-center mb-6">
                Cambiar Contraseña
            </h2>
            
            <form class="mt-4 space-y-4 lg:mt-5 md:space-y-5" id="cambioForm">
                @csrf
                <div>
                    <label for="nueva_contrasena" class="block mb-2 text-sm font-medium text-gray-900">
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="nueva_contrasena" 
                               id="nueva_contrasena" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                               required>
                        <button type="button" 
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                onclick="togglePassword('nueva_contrasena')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Cambiar Contraseña
                </button>
            </form>

            <div id="message" class="mt-4 text-sm font-medium text-center text-green-500"></div>
            <div id="error" class="mt-4 text-sm font-medium text-center text-red-500"></div>
        </div>
    </div>

    <script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('cambioForm');
        const messageDiv = document.getElementById('message');
        const errorDiv = document.getElementById('error');
        
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Limpiar mensajes anteriores
            messageDiv.textContent = '';
            errorDiv.textContent = '';
            
            const nueva_contrasena = document.getElementById('nueva_contrasena').value;
            
            // Validación de longitud mínima
            if (nueva_contrasena.length < 8) {
                errorDiv.textContent = 'La contraseña debe tener al menos 8 caracteres';
                return;
            }
            
            try {
                // Log para depuración
                console.log('Datos a enviar:', {
                    nueva_contrasena,
                });

                const response = await fetch('/cambiar-contrasena', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ 
                        nueva_contrasena: nueva_contrasena
                    })
                });
                
                // Log para depuración
                console.log('Status:', response.status);
                
                const data = await response.json();
                console.log('Respuesta:', data);
                
                if (response.ok) {
                    messageDiv.textContent = data.message || 'Contraseña cambiada correctamente';
                    form.reset();
                    
                    // Redirigir al login después de 2 segundos
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    errorDiv.textContent = data.message || data.error || 'Error al cambiar la contraseña';
                }
            } catch (error) {
                console.error('Error:', error);
                errorDiv.textContent = 'Error de conexión. Inténtalo de nuevo más tarde.';
            }
        });
    });
    </script>
</body>
</html>

