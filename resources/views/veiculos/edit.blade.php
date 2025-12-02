<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Veículo: {{ $veiculo->placa_modelo }}
        </h2>
    </x-slot>

    <style>
        @media (max-width: 640px) {
            .mobile-stacked-force {
                display: block !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 sm:py-6" x-data="{ 
        tab: 'geral', 
        mobile: window.matchMedia('(max-width: 640px)').matches 
    }" 
    x-init="$watch('mobile', value => console.log('Mobile state:', value)); window.matchMedia('(max-width: 640px)').addEventListener('change', e => mobile = e.matches);">
        
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200 overflow-x-auto overflow-y-hidden no-scrollbar hidden sm:block">
            <nav class="-mb-px flex space-x-8 min-w-max px-4 sm:px-0" aria-label="Tabs">
                <button @click="tab = 'geral'" 
                    :class="tab === 'geral' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Dados Gerais
                </button>

                <button @click="tab = 'tecnico'" 
                    :class="tab === 'tecnico' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Detalhes Técnicos
                </button>

                <button @click="tab = 'docs'" 
                    :class="tab === 'docs' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                    Documentação & Pesos
                </button>

                <button @click="tab = 'fotos'" 
                    :class="tab === 'fotos' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Fotos
                </button>
            </nav>
        </div>

        {{-- Main Form (Tabs 1-3) --}}
        <form method="POST" action="{{ route('veiculos.update', $veiculo->vei_id) }}" id="form-edit-veiculo">
            @csrf
            @method('PUT')
            @include('veiculos._form', ['veiculo' => $veiculo])
        </form>
        
        {{-- Photos Tab (Tab 4) --}}
        <div x-show="tab === 'fotos' || mobile" style="display: none;" class="animate-fade-in-up mobile-stacked-force">
            <x-veiculos.photo-gallery :veiculoId="$veiculo->vei_id" />
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end mt-8 mb-4" x-show="tab !== 'fotos' || mobile">
            <a href="{{ route('veiculos.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition shadow-sm font-medium">
                Cancelar
            </a>
            <button type="submit" form="form-edit-veiculo" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition shadow-sm font-medium">
                Salvar Veículo
            </button>
        </div>

    </div>
</x-app-layout>
