<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-50 p-6">
    <div class="p-6 bg-white rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-xl font-bold text-gray-900 text-center mb-6">
            Verificación de Código
        </h2>
        <div class="flex justify-center space-x-3 mb-6">
            @for ($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    id="code-{{ $i }}"
                    class="w-12 h-12 text-center text-lg font-medium border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                >
            @endfor
        </div>
        <button 
            type="button" 
            id="verify-button"
            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-4"
        >
            Verificar Código
        </button>
        <div class="text-center text-sm text-gray-500">
            <p id="timer-text">Reenviar código en 60 segundos</p>
            <button 
                type="button" 
                id="resend-button"
                class="hidden py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
            >
                Reenviar Código
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = Array.from({ length: 6 }, (_, i) => document.getElementById(`code-${i}`));
            const verifyButton = document.getElementById('verify-button');
            const timerText = document.getElementById('timer-text');
            const resendButton = document.getElementById('resend-button');
            let timeLeft = 60;
            let timer;

            startTimer();

            inputs.forEach((input, index) => {
               
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                   
                    if (!/^[0-9]?$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Move to next input if a digit was entered
                    if (value && index < 5) {
                        inputs[index + 1].focus();
                    }
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            // Verify button click handler
            verifyButton.addEventListener('click', async function() {
                const code = inputs.map(input => input.value).join('');
                try {
                    const response = await fetch('/verificar-codigo', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF Token
                        },
                        body: JSON.stringify({ codigo: code })
                    });

                    const data = await response.json();
                    if (response.ok) {
                        // Redirigir a la vista de cambio de contraseña si el código es correcto
                        window.location.href = '/cambiar-contrasena';
                    } else {
                        alert('Código incorrecto: ' + data.message);
                    }
                } catch (error) {
                    alert('Error al verificar el código');
                }
            });

            // Resend button click handler
            resendButton.addEventListener('click', function() {
                timeLeft = 60;
                resendButton.classList.add('hidden');
                timerText.classList.remove('hidden');
                startTimer();
                
                // Here you would typically call an API to resend the code
                // For example: axios.post('/resend-code');
            });

            function startTimer() {
                clearInterval(timer);
                timer = setInterval(function() {
                    timeLeft--;
                    timerText.textContent = `Reenviar código en ${timeLeft} segundos`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        timerText.classList.add('hidden');
                        resendButton.classList.remove('hidden');
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>