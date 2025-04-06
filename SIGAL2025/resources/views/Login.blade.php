<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-700 dark:text-white mb-4">DANIELA'S LEATHER</h2>
        <h2 class="text-2xl font-semibold text-center text-gray-700 dark:text-white mb-4">Iniciar sesión</h2>

        <form id="loginForm" class="space-y-4" action="/login" method="POST">
            @csrf
            <!-- Campo de Usuario -->
            <div>
                <label for="nombre_usuario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                    placeholder="Nombre Usuario" required maxlength="8" />
                <span id="nombre_usuario_error" class="text-sm text-red-600 hidden">El nombre de usuario solo puede contener letras y números, máximo 8 caracteres.</span>
            </div>

            <!-- Campo de Contraseña -->
            <div>
                <label for="contrasena" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="*******" required />
                <span id="contrasena_error" class="text-sm text-red-600 hidden">La contraseña es obligatoria.</span>
            </div>

            <!-- Recordarme -->
            <div class="flex items-center">
                <input id="remember" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                <label for="remember" class="ml-2 text-sm text-gray-900 dark:text-gray-300">Recordarme</label>
            </div>

            <!-- Botón de inicio de sesión -->
            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                Iniciar sesión
            </button>
        </form>

        <!-- Link de recuperar contraseña -->
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
            ¿Olvidaste tu contraseña? 
            <a href="{{ url('SolicitarRecuperacion') }}" class="text-blue-600 hover:underline dark:text-blue-400">Recupérala aquí</a>
        </p>
    </div>
    <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const username = document.getElementById("nombre_usuario").value;
    const password = document.getElementById("contrasena").value;
    const usernameError = document.getElementById("nombre_usuario_error");
    const passwordError = document.getElementById("contrasena_error");

    usernameError.classList.add("hidden");
    passwordError.classList.add("hidden");

    if (!/^[a-zA-Z0-9]{1,8}$/.test(username)) {
        usernameError.textContent = "El nombre de usuario solo puede contener letras y números, máximo 8 caracteres.";
        usernameError.classList.remove("hidden");
        return;
    }

    if (password.trim() === "") {
        passwordError.textContent = "La contraseña es obligatoria.";
        passwordError.classList.remove("hidden");
        return;
    }

    fetch("/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            nombre_usuario: username,
            contrasena: password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Error: " + data.error);
        } else if (data.redirect) {
            window.location.href = data.redirect; // Usa la URL de redirección del servidor
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error al conectar con el servidor");
    });
});
</script>
</body>
</html>

