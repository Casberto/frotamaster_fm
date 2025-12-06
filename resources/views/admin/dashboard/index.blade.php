<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel do Super Administrador') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ currentTab: 'overview' }">
        <div>
            
            {{-- Navigation Tabs --}}
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6 overflow-x-auto p-2 lg:p-0" aria-label="Tabs">
                    <button @click="currentTab = 'overview'"
                        :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': currentTab === 'overview', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300': currentTab !== 'overview' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        Visão Geral
                    </button>
                    <button @click="currentTab = 'clients'"
                        :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': currentTab === 'clients', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300': currentTab !== 'clients' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Clientes
                    </button>
                    <button @click="currentTab = 'licensing'"
                        :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': currentTab === 'licensing', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300': currentTab !== 'licensing' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Licenciamento
                    </button>
                    <button @click="currentTab = 'infrastructure'"
                        :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': currentTab === 'infrastructure', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300': currentTab !== 'infrastructure' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 0 1-3-3m3 3a3 3 0 1 0 0 6h13.5a3 3 0 1 0 0-6m-16.5-3a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3m-19.5 0a4.5 4.5 0 0 1 .9-2.7L5.737 5.1a3.375 3.375 0 0 1 2.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 0 1 .9 2.7m0 0a3 3 0 0 1-3 3m0 3h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Zm-3 6h.008v.008h-.008v-.008Zm0-6h.008v.008h-.008v-.008Z" />
                        </svg>
                        Infraestrutura
                    </button>

                </nav>
            </div>

            {{-- Tab Contents --}}
            <div class="space-y-6">
                
                <div x-show="currentTab === 'overview'" x-cloak>
                    @if($overview['licencas_vencendo'] > 0)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Atenção: Existem {{ $overview['licencas_vencendo'] }} licenças vencendo nos próximos 30 dias. Verifique a aba 'Licenciamento' ou 'Clientes'.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @include('admin.dashboard.partials.overview')
                </div>

                <div x-show="currentTab === 'clients'" x-cloak>
                    @include('admin.dashboard.partials.clients')
                </div>

                <div x-show="currentTab === 'licensing'" x-cloak>
                    @include('admin.dashboard.partials.licensing')
                </div>
                
                <div x-show="currentTab === 'infrastructure'" x-cloak>
                    @include('admin.dashboard.partials.infrastructure')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
