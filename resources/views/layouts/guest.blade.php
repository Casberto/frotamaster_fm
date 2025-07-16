<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Estilos customizados para a tela de login --}}
        <style>
            .login-bg {
                /* Cor de fundo escura para mobile, a mesma do card de login */
                background-color: #1f2937; /* Equivalente a bg-gray-800 */
            }
            @media (min-width: 640px) { /* sm: breakpoint de Tailwind */
                /* Degradê para desktop */
                .login-bg {
                    background: linear-gradient(135deg, #1A202C 0%, #3182CE 100%);
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- As classes 'pt-6 sm:pt-0' foram removidas para eliminar a borda branca no mobile --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center login-bg">
            {{-- O slot irá conter o card de login --}}
            {{ $slot }}
        </div>
    </body>
</html>
