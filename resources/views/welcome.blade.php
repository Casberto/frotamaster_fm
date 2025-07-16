<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Frotamaster - Gestão de Frotas Simplificada</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            .hero-bg {
                background-color: #1A202C;
                background-image: 
                    radial-gradient(circle at 15% 50%, rgba(49, 130, 206, 0.2), transparent 40%),
                    radial-gradient(circle at 85% 30%, rgba(45, 55, 72, 0.2), transparent 40%);
            }
            .feature-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-700">
        <div x-data="{ scrolled: false }" @scroll.window="scrolled = (window.scrollY > 10)">
            <!-- Header -->
            <header :class="{ 'bg-white/80 backdrop-blur-lg shadow-sm': scrolled, 'bg-transparent': !scrolled }" class="fixed inset-x-0 top-0 z-50 transition-colors duration-300">
                <nav class="flex items-center justify-between p-4 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <a href="/" class="-m-1.5 p-1.5 flex items-center space-x-2">
                            <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-10 w-auto">
                            <span :class="{ 'text-gray-900': scrolled, 'text-white': !scrolled }" class="text-2xl font-bold transition-colors">Frotamaster</span>
                        </a>
                    </div>
                    <div class="flex lg:flex-1 lg:justify-end">
                        <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">Acessar Sistema <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </nav>
            </header>

            <main>
                <!-- Hero Section -->
                <section class="relative isolate px-6 lg:px-8 hero-bg">
                    <div class="mx-auto max-w-3xl py-32 sm:py-48 lg:py-56 text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">A gestão da sua frota, finalmente simplificada.</h1>
                        <p class="mt-6 text-lg leading-8 text-gray-300">Deixe a complexidade para trás. Com o Frotamaster, você tem o controle total sobre manutenções, abastecimentos e custos, de forma inteligente e intuitiva.</p>
                        <div class="mt-10">
                            <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-5 py-3 text-base font-semibold text-white shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition">Começar agora</a>
                        </div>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="bg-white py-24 sm:py-32" x-data="{
                    observe(el) {
                        new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.remove('opacity-0', 'translate-y-4');
                                }
                            });
                        }, { threshold: 0.1 }).observe(el);
                    }
                }">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-2xl lg:text-center">
                            <h2 class="text-base font-semibold leading-7 text-blue-600">Recursos Poderosos</h2>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Tudo que você precisa, sem complicação</p>
                            <p class="mt-6 text-lg leading-8 text-gray-600">Desenvolvemos cada funcionalidade pensando na sua rotina, para que você gaste menos tempo gerenciando e mais tempo crescendo.</p>
                        </div>
                        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-8 sm:mt-20 lg:mt-24 lg:max-w-none lg:grid-cols-3">
                            
                            <div class="feature-card flex flex-col items-center p-8 bg-gray-50 rounded-2xl opacity-0 translate-y-4 transition-all duration-700" x-ref="card1" x-init="observe($refs.card1)">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.472-2.472a3.375 3.375 0 00-4.773-4.773L4.75 15.17l2.472-2.472a3.375 3.375 0 004.773-4.773z" /></svg>
                                </div>
                                <h3 class="mt-5 text-xl font-semibold leading-7 text-gray-900">Manutenções em Dia</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600 text-center">Registre serviços, agende revisões por data ou KM e receba alertas automáticos. Evite quebras e custos inesperados.</p>
                            </div>

                            <div class="feature-card flex flex-col items-center p-8 bg-gray-50 rounded-2xl opacity-0 translate-y-4 transition-all duration-700 delay-200" x-ref="card2" x-init="observe($refs.card2)">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h.01M8.25 12h.01M8.25 16.5h.01M12 7.5h.01M12 12h.01M12 16.5h.01M15.75 7.5h.01M15.75 12h.01M15.75 16.5h.01M4.5 12a7.5 7.5 0 0115 0v2.25a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 14.25V12z" /></svg>
                                </div>
                                <h3 class="mt-5 text-xl font-semibold leading-7 text-gray-900">Controle de Combustível</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600 text-center">Monitore cada abastecimento, analise o consumo médio e identifique veículos com gasto excessivo de forma simples e rápida.</p>
                            </div>

                            <div class="feature-card flex flex-col items-center p-8 bg-gray-50 rounded-2xl opacity-0 translate-y-4 transition-all duration-700 delay-400" x-ref="card3" x-init="observe($refs.card3)">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3M3.75 3v1.5M20.25 3v1.5M3.75 19.5h16.5M5.25 7.5h13.5" /></svg>
                                </div>
                                <h3 class="mt-5 text-xl font-semibold leading-7 text-gray-900">Dashboard Inteligente</h3>
                                <p class="mt-2 text-base leading-7 text-gray-600 text-center">Todos os dados importantes da sua frota consolidados em um painel visual. Tome decisões baseadas em informações, não em suposições.</p>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <footer class="bg-gray-900">
                    <div class="mx-auto max-w-7xl overflow-hidden px-6 py-12 lg:px-8">
                        <p class="text-center text-xs leading-5 text-gray-400">&copy; {{ date('Y') }} Frotamaster. Todos os direitos reservados.</p>
                    </div>
                </footer>
            </main>
        </div>
    </body>
</html>