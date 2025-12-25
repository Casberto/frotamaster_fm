<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status #{{ $os->osv_codigo }} - Frotamaster</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0 bg-green-50">
        
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
            
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h2 class="text-2xl font-black text-gray-900 mb-2">Serviço Aprovado!</h2>
            <p class="text-gray-600 mb-6">Obrigado, <strong>{{ explode(' ', $os->veiculo->cliente->clo_nome)[0] }}</strong>. A oficina já foi notificada e iniciará os trabalhos no seu <strong>{{ $os->veiculo->vct_modelo }}</strong>.</p>

            <div class="bg-gray-50 rounded p-4 mb-6 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500 text-sm">Status Atual:</span>
                    <span class="font-bold text-blue-600 uppercase text-sm">{{ $os->osv_status }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Total Aprovado:</span>
                    <span class="font-bold text-gray-900">R$ {{ number_format($os->osv_valor_total, 2, ',', '.') }}</span>
                </div>
            </div>

            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $os->empresa->telefone ?? '') }}" class="text-blue-600 font-bold hover:underline">
                Falar com a oficina
            </a>
        </div>
    </div>
</body>
</html>
