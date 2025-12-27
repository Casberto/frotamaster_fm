<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Frotamaster') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js (Garantia caso não esteja no bundle) -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
        <meta name="theme-color" content="#1f2937">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Frotamaster">

        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/ios/icon-180.png') }}">
    </head>
    <body class="h-full antialiased text-slate-900">
        {{ $slot }}
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('Frotamaster SW registrado com escopo:', registration.scope);
                        }, function(err) {
                            console.log('Falha no registro do SW:', err);
                        });
                });
            }
        </script>
    </body>
</html>