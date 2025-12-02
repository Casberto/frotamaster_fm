<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Perfil') }}
        </h2>
    </x-slot>

    <style>
        @media (max-width: 640px) {
            .mobile-stacked-force {
                display: block !important;
            }
        }
    </style>

    @php
        // Get the first module name to set as default active tab
        $firstModule = \App\Models\Permissao::select('prm_modulo')->distinct()->orderBy('prm_modulo')->value('prm_modulo') ?? '';
    @endphp

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6" 
    x-data="{ 
        tab: 'geral', 
        moduleTab: '{{ $firstModule }}',
        mobile: window.matchMedia('(max-width: 640px)').matches,
        
        toggleAll(checked) {
            document.querySelectorAll('input[name=\'permissoes[]\']').forEach(el => {
                el.checked = checked;
            });
        },

        toggleModule(moduleName, checked) {
            const container = document.getElementById('module-' + moduleName);
            if (container) {
                container.querySelectorAll('input[name=\'permissoes[]\']').forEach(el => {
                    el.checked = checked;
                });
            }
        }
    }" 
    x-init="window.matchMedia('(max-width: 640px)').addEventListener('change', e => mobile = e.matches);"
    @invalid.capture.window="
        const target = $event.target;
        if (document.getElementById('tab-geral-content') && document.getElementById('tab-geral-content').contains(target)) {
            tab = 'geral';
        }
    ">
        
        {{-- Top Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200 overflow-x-auto overflow-y-hidden no-scrollbar hidden sm:block">
            <nav class="-mb-px flex space-x-8 min-w-max px-4 sm:px-0" aria-label="Tabs">
                <button @click="tab = 'geral'" 
                    :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Dados Cadastrais
                </button>
            </nav>
        </div>

        <form action="{{ route('perfis.store') }}" method="POST">
            @csrf
            @include('perfis._form', ['perfil' => new \App\Models\Perfil()])
        </form>
    </div>
</x-app-layout>
