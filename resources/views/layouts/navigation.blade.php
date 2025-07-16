
<nav x-data="{ open: '' }" class="sidebar h-full flex flex-col justify-between">
    <div>
        <!-- Logo -->
        <div class="p-4 flex justify-center">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-20 w-auto">
            </a>
        </div>

        <div class="mt-4 space-y-1">
            <x-nav-link-custom :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                <x-slot name="icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </x-slot>
                Dashboard
            </x-nav-link-custom>

            {{-- =============================================== --}}
            {{-- MENU DO CLIENTE (USUÁRIO MASTER/COMUM) --}}
            {{-- =============================================== --}}
            @if(auth()->user()->role !== 'super-admin')
                {{-- Módulo Frota --}}
                <div>
                    {{-- Módulo Veículos --}}
                    <button @click="open = (open === 'veiculos' ? '' : 'veiculos')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </span>
                            <span>Frota</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'cliente'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    {{-- Funções do Módulo Veículos --}}
                    <div x-show="open === 'veiculos'" x-transition class="pl-8 space-y-1 mt-1">
                        {{-- Listar Veículos --}}
                        <a href="{{ route('veiculos.index') }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 @if(request()->routeIs('veiculos.index')) text-blue-400 font-bold @endif">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                            </span>
                            <span>Listar Veículos</span>
                        </a>

                        {{-- Novo veículo --}}
                        <a href="{{ route('veiculos.create') }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 @if(request()->routeIs('veiculos.create')) text-blue-400 font-bold @endif">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                            <span>Novo Veículo</span>
                        </a>

                        {{-- Pendências do Veículo --}}
                        <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-500">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                            </span>
                            <span>* Pendências</span>
                        </a>

                        {{-- Relatórios do Veículo --}}
                        <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-500">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                </svg>
                            </span>
                            <span>* Relatórios</span>
                        </a>
                    </div>
                </div>


                {{-- Módulo Manutenções --}}
                <div>
                    <button @click="open = (open === 'manutencoes' ? '' : 'manutencoes')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                                </svg>
                            </span>
                            <span>Manutenções</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'manutencoes'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open === 'manutencoes'" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('manutencoes.index') }}" class="sidebar-submenu-link @if(request()->routeIs('manutencoes.index')) active @endif">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>
                            <span>Listar Manutenções</span>
                        </a>
                        <a href="{{ route('manutencoes.create') }}" class="sidebar-submenu-link @if(request()->routeIs('manutencoes.create')) active @endif">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>
                            <span>Nova Manutenção</span>
                        </a>
                        <a href="#" class="sidebar-submenu-link disabled">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg></span>
                            <span>* Pendências</span>
                        </a>
                        <a href="#" class="sidebar-submenu-link disabled">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg></span>
                            <span>* Relatórios</span>
                        </a>
                    </div>
                </div>

                {{-- Módulo Abastecimentos --}}
                <div>
                    <button @click="open = (open === 'abastecimentos' ? '' : 'abastecimentos')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                </svg>
                            </span>
                            <span>Abastecimentos</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'abastecimentos'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open === 'abastecimentos'" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('abastecimentos.index') }}" class="sidebar-submenu-link @if(request()->routeIs('abastecimentos.index')) active @endif">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>
                            <span>Listar Abastecimentos</span>
                        </a>
                        <a href="{{ route('abastecimentos.create') }}" class="sidebar-submenu-link @if(request()->routeIs('abastecimentos.create')) active @endif">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>
                            <span>Novo Abastecimento</span>
                        </a>
                        <a href="#" class="sidebar-submenu-link disabled">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg></span>
                            <span>* Pendências</span>
                        </a>
                        <a href="#" class="sidebar-submenu-link disabled">
                            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg></span>
                            <span>* Relatórios</span>
                        </a>
                    </div>
                </div>

                {{-- Módulo Documentos --}}
                <div>
                    <button @click="open = (open === 'documentos' ? '' : 'documentos')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375-3.375-3.375m0 0A9.023 9.023 0 0 1 12 5.25c-2.432 0-4.62.962-6.244 2.557m12.488 6.443-3.375-3.375M12 12.75h.008v.008H12v-.008Z" /></svg>
                            </span>
                            <span>Documentos</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'documentos'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open === 'documentos'" x-transition class="pl-8 space-y-1 mt-1">
                        <p class="px-4 pt-2 pb-1 text-xs text-gray-400 uppercase">Motoristas</p>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>* Listar Documentos</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>* Novo Documento</a>
                        
                        <p class="px-4 pt-2 pb-1 text-xs text-gray-400 uppercase">Veículos</p>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>* Listar Documentos</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>* Novo Documento</a>

                        <p class="px-4 pt-2 pb-1 text-xs text-gray-400 uppercase">Seguros</p>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>* Listar Seguros</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>* Novo Seguro</a>

                        <div class="border-t border-gray-700 my-2"></div>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg></span>* Pendências</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg></span>* Relatórios</a>
                    </div>
                </div>

                {{-- Módulo Usuários --}}
                <div>
                    <button @click="open = (open === 'usuarios' ? '' : 'usuarios')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-2.253a9.527 9.527 0 0 0-1.242-1.242M15 19.128L11.625 15.75M15 19.128A9.37 9.37 0 0 1 12 21a9.37 9.37 0 0 1-3-5.672M15 19.128A9.363 9.363 0 0 0 12 15.75c-1.028 0-2.015.187-2.933.533M6.375 21V9.375m0 0a9.37 9.37 0 0 1 1.933-5.334A9.37 9.37 0 0 1 12 3c1.933 0 3.817.566 5.333 1.5M6.375 21a9.375 9.375 0 0 1-1.933-5.334M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 0 1 3-3A3 3 0 0 1 15 4.5v8.25a3 3 0 0 1-3 3Z" /></svg>
                            </span>
                            <span>Usuários</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'usuarios'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open === 'usuarios'" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>* Listar Usuários</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>* Novo Usuário</a>
                        <div class="border-t border-gray-700 my-2"></div>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg></span>* Listar Perfis</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>* Novo Perfil</a>
                    </div>
                </div>

                {{-- Módulo Configurações --}}
                <div>
                    <button @click="open = (open === 'configuracoes' ? '' : 'configuracoes')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a6.759 6.759 0 0 1 0 1.905c-.008.379.137.752.43.992l1.004.827a1.125 1.125 0 0 1 .26 1.431l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.333.183-.582.495-.645.87l-.213 1.28c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.759 6.759 0 0 1 0-1.905c.008-.379-.137-.752-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.431l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.87l.213-1.281Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </span>
                            <span>Configurações</span>
                        </div>
                        <svg :class="{'rotate-180': open === 'configuracoes'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div x-show="open === 'configuracoes'" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" /></svg></span>* Parâmetros</a>
                        <a href="#" class="sidebar-submenu-link disabled"><span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg></span>* Sobre</a>
                    </div>
                </div>
            @endif

            {{-- =============================================== --}}
            {{-- MENU DO SUPER ADMIN --}}
            {{-- =============================================== --}}
            @if(auth()->user()->role === 'super-admin')
                 <div>
                    <button @click="open = (open === 'admin' ? '' : 'admin')" class="sidebar-link w-full flex justify-between items-center">
                        <div class="flex items-center">
                            <span class="mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                            </span>
                            <span>Administrativo</span>
                        </div>

                    </button>
                    <div x-show="open === 'admin'" x-transition class="pl-8 space-y-1 mt-1">
                        <a href="{{ route('admin.empresas.index') }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 @if(request()->routeIs('admin.empresas.*')) text-blue-400 font-bold @endif">Gerenciar Empresas</a>
                        <a href="#" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-700 text-gray-500">* Gerenciar Licenças</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-nav-link-custom :href="route('logout')" :active="false" onclick="event.preventDefault(); this.closest('form').submit();">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg></x-slot>
                Sair
            </x-nav-link-custom>
        </form>
    </div>
</nav>