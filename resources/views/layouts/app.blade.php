<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-f">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Frotamaster') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- CORREÇÃO: Removendo os scripts AlpineJS da CDN. -->
        <!-- Eles já estão sendo carregados via 'resources/js/app.js' pelo Vite. -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script> -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Nosso CSS Customizado -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">
            <!-- Sidebar -->
            <aside 
                class="w-64 flex-shrink-0 fixed inset-y-0 left-0 z-40 transform lg:translate-x-0 transition-transform duration-200 ease-in-out"
                :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            >
                @include('layouts.navigation')
            </aside>

            <!-- Overlay para fechar o menu no mobile -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-30 lg:hidden"></div>

            <!-- Conteúdo Principal -->
            <div class="flex-1 main-content lg:ml-64">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white border-b border-gray-200">
                        <div class="max-w-full mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center">
                            <!-- Botão Sanduíche para Mobile -->
                            <button @click.stop="sidebarOpen = !sidebarOpen" class="lg:hidden mr-4 text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="p-6 sm:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')
        @stack('scripts')

        {{-- SCRIPT LIBS --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                // Máscaras de Contato e Empresa
                $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
                var SPMaskBehavior = function (val) {
                  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                  onKeyPress: function(val, e, field, options) {
                      field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
                $('#telefone_contato').mask(SPMaskBehavior, spOptions);

                // Máscaras do Formulário de Veículos
                $('#placa').mask('SSS-AAAA', {
                    'translation': {
                        S: {pattern: /[A-Za-z]/},
                        A: {pattern: /[A-Za-z0-9]/}
                    },
                    onKeyPress: function(val, e, field, options) {
                        field.val(val.toUpperCase());
                    }
                });
                $('#renavam').mask('00000000000');
                $('#quilometragem_atual').mask('000000', {reverse: true}); // Máscara adicionada
                $('#chassi').on('input', function() {
                    $(this).val($(this).val().toUpperCase().replace(/[^A-HJ-NPR-Z0-9]/g, ''));
                });
            });
        </script>
    </body>
</html>