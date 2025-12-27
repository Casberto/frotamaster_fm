<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Frotamaster - Offline</title>
    <!-- Usando Tailwind CDN para garantir estilo bonito mesmo offline se estiver em cache, mas faremos styles inline caso o CDN falhe -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Fallback básico se o CDN falhar */
        body { font-family: ui-sans-serif, system-ui, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 90%; width: 400px; text-align: center; }
        
        /* Animação do Timer */
        .timer-circle {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #eef2ff;
        }
        .timer-text { font-size: 2.5rem; font-weight: 800; color: #3b82f6; }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 450px;
            width: 100%;
            text-align: left;
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center p-4">

    <div class="card p-8 rounded-2xl bg-white shadow-xl max-w-md w-full text-center relative overflow-hidden">
        <!-- Logo -->
        <div class="mb-6 flex justify-center">
             <img src="/img/logo.svg" alt="Frotamaster Logo" class="h-24 w-auto drop-shadow-sm">
        </div>

        <!-- Título -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Sem Conexão</h1>
        <p class="text-gray-500 mb-8">Parece que você está offline no momento.</p>

        <!-- Timer Visual -->
        <div class="timer-circle mb-6">
            <svg class="absolute w-full h-full transform -rotate-90 pointer-events-none">
                 <circle cx="60" cy="60" r="54" stroke="#dbeafe" stroke-width="8" fill="none" />
                 <circle id="progress-ring" cx="60" cy="60" r="54" stroke="#3b82f6" stroke-width="8" fill="none" 
                         stroke-dasharray="339.292" stroke-dashoffset="0" transition="stroke-dashoffset 1s linear" />
            </svg>
            <span id="timer" class="timer-text font-bold text-4xl text-blue-500">5</span>
        </div>

        <p class="text-sm text-gray-400 mb-6">Reconectando automaticamente...</p>

        <!-- Botões -->
        <div class="flex flex-col gap-3">
            <button onclick="window.location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-md active:scale-95">
                Tentar Agora
            </button>
            <button onclick="openModal()" class="w-full bg-white hover:bg-gray-50 text-gray-600 font-medium py-3 px-4 rounded-xl border border-gray-200 transition-all">
                Precisa de Ajuda?
            </button>
        </div>
    </div>

    <!-- Modal de Ajuda -->
    <div id="helpModal" class="modal-overlay">
        <div class="modal-content shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Por que estou offline?</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="space-y-4 text-gray-600">
                <div class="flex gap-4">
                    <div class="bg-blue-100 p-2 rounded-lg h-fit text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Sem Internet</p>
                        <p class="text-sm">Verifique se o Wi-Fi ou dados móveis estão ativos.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="bg-orange-100 p-2 rounded-lg h-fit text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Modo Avião</p>
                        <p class="text-sm">Certifique-se de que o modo avião está desativado.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="bg-gray-100 p-2 rounded-lg h-fit text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Servidor</p>
                        <p class="text-sm">Ocasionalmente o servidor pode estar em manutenção.</p>
                    </div>
                </div>
            </div>

            <button onclick="closeModal()" class="mt-6 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg transition-colors">
                Entendi
            </button>
        </div>
    </div>

    <script>
        // Setup do Timer
        let timeLeft = 5;
        const timerEl = document.getElementById('timer');
        const ringEl = document.getElementById('progress-ring');
        
        // Circunferência para cálculo do dashoffset (2 * PI * r)
        // r = 54 -> C = 339.292
        const circumference = 339.292;
        ringEl.style.strokeDasharray = `${circumference} ${circumference}`;
        
        function setProgress(percent) {
            const offset = circumference - (percent / 100) * circumference;
            // ringEl.style.strokeDashoffset = offset;
            // Para fazer animação fluida, vamos via CSS transition ou step a step do timer
        }

        const interval = setInterval(() => {
            timeLeft--;
            timerEl.textContent = timeLeft;
            
            // Atualiza anel (opcional, só visual)
            const percent = ((5 - timeLeft) / 5) * 100;
            // setProgress(percent); 

            if (timeLeft <= 0) {
                clearInterval(interval);
                timerEl.textContent = "↻";
                window.location.reload();
            }
        }, 1000);

        // Funções do Modal
        function openModal() {
            document.getElementById('helpModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('helpModal').style.display = 'none';
        }

        // Fechar modal ao clicar fora
        document.getElementById('helpModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
