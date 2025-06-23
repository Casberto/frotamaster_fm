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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        {{-- SCRIPT LIBS --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        {{-- SCRIPT PARA APLICAR AS MÁSCARAS (VERSÃO MAIS ROBUSTA) --}}
 <script type="text/javascript">
            window.onload = function() {
                try {
                    // Máscara de CNPJ
                    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});

                    // Máscara dinâmica para Telefone/Celular
                    var SPMaskBehavior = function (val) {
                      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    spOptions = {
                      onKeyPress: function(val, e, field, options) {
                          field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };

                    $('#telefone_contato').mask(SPMaskBehavior, spOptions);

                } catch (e) {
                    console.error("Erro ao aplicar máscara: ", e);
                }
            };
        </script>
    </body>
</html>
