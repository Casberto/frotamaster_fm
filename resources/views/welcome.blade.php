<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Frotamaster - Gestão de Frotas que Realmente Funciona</title>
        <meta name="description" content="Controle total sobre manutenções, abastecimentos e custos da sua frota de forma inteligente e intuitiva. Reduza custos em até 30%. Teste grátis por 30 dias.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .hero-bg {
                background-color: #111827; /* bg-gray-900 */
                background-image: 
                    radial-gradient(circle at 15% 50%, rgba(37, 99, 235, 0.15), transparent 40%),
                    radial-gradient(circle at 85% 30%, rgba(55, 65, 81, 0.15), transparent 40%);
            }
            .feature-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.20);
            }
            .cta-bg {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-700">
        <div x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.scrollY > 20)">
            <!-- Header -->
            <header :class="{ 'bg-white/90 backdrop-blur-lg shadow-md': scrolled, 'bg-transparent': !scrolled }" class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
                <nav class="flex items-center justify-between p-4 lg:px-8" aria-label="Global">
                    <div class="flex lg:flex-1">
                        <a href="/" class="-m-1.5 p-1.5 flex items-center space-x-3">
                            <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-10 w-auto transition-all duration-300" :class="{ 'filter invert': scrolled }">
                            <span :class="{ 'text-gray-900': scrolled, 'text-white': !scrolled }" class="text-2xl font-bold tracking-tight transition-colors duration-300">Frotamaster</span>
                        </a>
                    </div>
                    
                    <!-- Hamburger Button (Mobile) -->
                    <div class="flex lg:hidden">
                        <button type="button" @click="mobileMenuOpen = true" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5" :class="{'text-gray-700': scrolled, 'text-gray-300': !scrolled}">
                            <span class="sr-only">Abrir menu principal</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Desktop Links -->
                    <div class="hidden lg:flex lg:gap-x-12">
                        <a href="#recursos" :class="{'text-gray-700 hover:text-blue-600': scrolled, 'text-gray-300 hover:text-white': !scrolled}" class="text-sm font-semibold leading-6 transition-colors">Recursos</a>
                        <a href="#depoimentos" :class="{'text-gray-700 hover:text-blue-600': scrolled, 'text-gray-300 hover:text-white': !scrolled}" class="text-sm font-semibold leading-6 transition-colors">Depoimentos</a>
                        <a href="{{ route('company.register') }}" :class="{'text-gray-700 hover:text-blue-600': scrolled, 'text-gray-300 hover:text-white': !scrolled}" class="text-sm font-semibold leading-6 transition-colors">Cadastre-se</a>
                    </div>

                    <!-- Desktop Login Button -->
                    <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                        <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-transform hover:scale-105">
                            Acessar Sistema <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </nav>

                <!-- Mobile Menu -->
                <div x-show="mobileMenuOpen" class="lg:hidden" x-cloak>
                    <div class="fixed inset-0 z-50 bg-black bg-opacity-25" @click="mobileMenuOpen = false"></div>
                    <div 
                        x-show="mobileMenuOpen"
                        x-transition:enter="transform transition ease-in-out duration-300"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-300"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-white/10">
                        <div class="flex items-center justify-between">
                             <a href="/" class="-m-1.5 p-1.5 flex items-center space-x-3">
                                <img class="h-10 w-auto" src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo">
                                <span class="text-2xl font-bold tracking-tight text-white">Frotamaster</span>
                            </a>
                            <button type="button" @click="mobileMenuOpen = false" class="-m-2.5 rounded-md p-2.5 text-gray-400">
                                <span class="sr-only">Fechar menu</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-6 flow-root">
                            <div class="-my-6 divide-y divide-gray-500/25">
                                <div class="space-y-2 py-6">
                                    <a @click="mobileMenuOpen = false" href="#recursos" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Recursos</a>
                                    <a @click="mobileMenuOpen = false" href="#depoimentos" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Depoimentos</a>
                                    <a @click="mobileMenuOpen = false" href="{{ route('company.register') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-white hover:bg-gray-800">Cadastre-se</a>
                                </div>
                                <div class="py-6">
                                    <a href="{{ route('login') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-white hover:bg-gray-800">Acessar Sistema</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main>
                <!-- Hero Section -->
                <section class="relative isolate px-6 pt-14 lg:px-8 hero-bg">
                    <div class="mx-auto max-w-4xl py-32 sm:py-48 lg:py-56 text-center">
                        <div class="mb-8 flex items-center justify-center gap-x-4">
                            <span class="relative inline-flex items-center gap-x-2 rounded-full bg-gray-700/50 px-4 py-1.5 text-sm font-medium text-white ring-1 ring-inset ring-white/10">
                                Teste grátis por 30 dias
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c.321-.772 1.415-.772 1.736 0l1.21 2.923c.18.435.594.735 1.07.78l3.22.47c.805.117 1.127 1.107.548 1.66l-2.33 2.273a1.25 1.25 0 00-.364 1.118l.55 3.208c.137.8-.702 1.418-1.423.998L12.01 15.63a1.25 1.25 0 00-1.15 0l-2.872 1.51c-.721.42-1.56-.198-1.423-.998l.55-3.208a1.25 1.25 0 00-.364-1.118L2.27 8.718c-.58-.552-.258-1.543.548-1.66l3.22-.47a1.25 1.25 0 001.07-.78l1.21-2.923z" clip-rule="evenodd" /></svg>
                            </span>
                            <span class="text-sm font-medium text-gray-300">Sem cartão de crédito</span>
                        </div>
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">Gestão de frotas que <span class="text-blue-400">realmente funciona</span></h1>
                        <p class="mt-6 text-lg leading-8 text-gray-300">Pare de perder tempo com planilhas. Tenha o controle total sobre manutenções, abastecimentos e custos da sua frota de forma inteligente e intuitiva.</p>
                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="{{ route('company.register') }}" class="rounded-md bg-white px-8 py-4 text-base font-semibold text-blue-600 shadow-lg hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-transform hover:scale-105">Começar Agora - É Grátis</a>
                            <a href="#recursos" class="text-sm font-semibold leading-6 text-white">Ver recursos <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                </section>

                <!-- Logos Section -->
                <div class="bg-white py-12 sm:py-16">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <p class="text-center text-sm font-semibold text-gray-500">Usado por empresas que buscam eficiência e controle</p>
                        <div class="mx-auto mt-10 grid max-w-lg grid-cols-4 items-center gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5">
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="https://placehold.co/158x48/cbd5e1/475569?text=LogoEmpresa" alt="Empresa 1" width="158" height="48">
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="https://placehold.co/158x48/cbd5e1/475569?text=Cliente" alt="Empresa 2" width="158" height="48">
                            <img class="col-span-2 max-h-12 w-full object-contain lg:col-span-1" src="https://placehold.co/158x48/cbd5e1/475569?text=Parceiro" alt="Empresa 3" width="158" height="48">
                            <img class="col-span-2 max-h-12 w-full object-contain sm:col-start-2 lg:col-span-1" src="https://placehold.co/158x48/cbd5e1/475569?text=Transporte" alt="Empresa 4" width="158" height="48">
                            <img class="col-span-2 col-start-2 max-h-12 w-full object-contain sm:col-start-auto lg:col-span-1" src="https://placehold.co/158x48/cbd5e1/475569?text=Logistica" alt="Empresa 5" width="158" height="48">
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <section id="recursos" class="bg-gray-50 py-24 sm:py-32" x-data="{
                    observe(el) {
                        new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    entry.target.classList.remove('opacity-0', 'translate-y-8');
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
                        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-10 sm:mt-20 lg:mt-24 lg:max-w-none lg:grid-cols-3">
                            
                            <div class="feature-card flex flex-col items-center p-8 bg-white rounded-2xl shadow-lg opacity-0 translate-y-8 transition-all duration-500" x-ref="card1" x-init="observe($refs.card1)">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17 4.872 21.01a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" /></svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold leading-7 text-gray-900">Manutenções em Dia</h3>
                                <p class="mt-4 text-base leading-7 text-gray-600 text-center">Registre serviços, agende revisões por data ou KM e receba alertas automáticos. Evite quebras e custos inesperados.</p>
                            </div>

                            <div class="feature-card flex flex-col items-center p-8 bg-white rounded-2xl shadow-lg opacity-0 translate-y-8 transition-all duration-500 delay-150" x-ref="card2" x-init="observe($refs.card2)">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h.01M8.25 12h.01M8.25 16.5h.01M12 7.5h.01M12 12h.01M12 16.5h.01M15.75 7.5h.01M15.75 12h.01M15.75 16.5h.01M4.5 12a7.5 7.5 0 0 1 15 0v2.25a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 14.25V12Z" /></svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold leading-7 text-gray-900">Controle de Combustível</h3>
                                <p class="mt-4 text-base leading-7 text-gray-600 text-center">Monitore cada abastecimento, analise o consumo médio e identifique veículos com gasto excessivo de forma simples e rápida.</p>
                            </div>

                            <div class="feature-card flex flex-col items-center p-8 bg-white rounded-2xl shadow-lg opacity-0 translate-y-8 transition-all duration-500 delay-300" x-ref="card3" x-init="observe($refs.card3)">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>
                                </div>
                                <h3 class="mt-6 text-xl font-semibold leading-7 text-gray-900">Dashboard Inteligente</h3>
                                <p class="mt-4 text-base leading-7 text-gray-600 text-center">Todos os dados importantes da sua frota consolidados em um painel visual. Tome decisões baseadas em informações, não em suposições.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section id="depoimentos" class="bg-white py-24 sm:py-32">
                    <div class="mx-auto max-w-7xl px-6 lg:px-8">
                        <div class="mx-auto max-w-xl text-center">
                            <h2 class="text-lg font-semibold leading-8 tracking-tight text-blue-600">Depoimentos</h2>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">O que nossos clientes dizem</p>
                        </div>
                        <div class="mx-auto mt-16 flow-root max-w-2xl sm:mt-20 lg:mx-0 lg:max-w-none">
                            <div class="-mt-8 sm:-mx-4 sm:columns-2 sm:text-[0] lg:columns-3">
                                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure class="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote class="text-gray-900">
                                            <p>“O Frotamaster tirou um peso enorme das nossas costas. O controle de manutenções preventivas é fantástico e já evitou que dois dos nossos caminhões ficassem parados na estrada. Recomendo!”</p>
                                        </blockquote>
                                        <figcaption class="mt-6 flex items-center gap-x-4">
                                            <img class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/e2e8f0/475569?text=JS" alt="">
                                            <div>
                                                <div class="font-semibold text-gray-900">João Silva</div>
                                                <div class="text-gray-600">Gerente de Logística, Transportadora Veloz</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure class="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote class="text-gray-900">
                                            <p>“Finalmente uma ferramenta que entende a nossa necessidade. O dashboard é limpo e vai direto ao ponto. Em menos de um mês, identificamos um veículo que estava com consumo 20% acima da média.”</p>
                                        </blockquote>
                                        <figcaption class="mt-6 flex items-center gap-x-4">
                                            <img class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/e2e8f0/475569?text=MP" alt="">
                                            <div>
                                                <div class="font-semibold text-gray-900">Maria Pereira</div>
                                                <div class="text-gray-600">Sócia-proprietária, Agro-Pecuária Boa Terra</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                                 <div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure class="rounded-2xl bg-gray-50 p-8 text-sm leading-6">
                                        <blockquote class="text-gray-900">
                                            <p>“A simplicidade para registrar um abastecimento ou uma manutenção é o que mais gosto. Nossa equipe toda aderiu rápido e agora temos todos os dados centralizados. Adeus, planilhas!”</p>
                                        </blockquote>
                                        <figcaption class="mt-6 flex items-center gap-x-4">
                                            <img class="h-10 w-10 rounded-full bg-gray-50" src="https://placehold.co/40x40/e2e8f0/475569?text=RF" alt="">
                                            <div>
                                                <div class="font-semibold text-gray-900">Roberto Faria</div>
                                                <div class="text-gray-600">Diretor de Operações, Construtora Edificar</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Final CTA Section -->
                <section class="relative isolate overflow-hidden cta-bg">
                    <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
                        <div class="mx-auto max-w-2xl text-center">
                            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Pronto para transformar a gestão da sua frota?</h2>
                            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-blue-100">Comece seu teste gratuito de 30 dias agora mesmo. Sem compromisso, sem necessidade de cartão de crédito. Simples assim.</p>
                            <div class="mt-10 flex items-center justify-center gap-x-6">
                                <a href="{{ route('company.register') }}" class="rounded-md bg-white px-8 py-4 text-base font-semibold text-blue-600 shadow-lg hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-transform hover:scale-105">Começar Agora - É Grátis</a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900">
                <div class="mx-auto max-w-7xl overflow-hidden px-6 py-12 lg:px-8">
                     <div class="flex justify-center space-x-10">
                        <a href="#recursos" class="text-sm leading-6 text-gray-400 hover:text-white">Recursos</a>
                        <a href="#depoimentos" class="text-sm leading-6 text-gray-400 hover:text-white">Depoimentos</a>
                        <a href="{{ route('company.register') }}" class="text-sm leading-6 text-gray-400 hover:text-white">Cadastro</a>
                     </div>
                    <p class="mt-10 text-center text-xs leading-5 text-gray-500">&copy; {{ date('Y') }} Frotamaster. Todos os direitos reservados.</p>
                </div>
            </footer>
        </div>
    </body>
</html>

