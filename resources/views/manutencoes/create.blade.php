<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nova Manutenção') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('manutencoes.store') }}">
                        @csrf
                        
                        {{-- ADICIONADO: Campo oculto para saber para onde voltar --}}
                        @if(request('reserva_id'))
                            <input type="hidden" name="reserva_id" value="{{ request('reserva_id') }}">
                        @endif

                        {{-- Passa o ID do veículo e fornecedor (se vierem da URL) --}}
                        @include('manutencoes._form', [
                            'manutencao' => new \App\Models\Manutencao(),
                            'veiculo_id' => request('veiculo_id'),
                            'fornecedor_id' => request('fornecedor_id')
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>