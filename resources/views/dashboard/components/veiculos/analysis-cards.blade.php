{{-- resources/views/dashboard/components/veiculos/analysis-cards.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    {{-- Custo Médio por KM --}}
    {{--<div class="p-4 bg-white rounded-lg shadow group">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-100 rounded-full">
                <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.75A.75.75 0 0 1 3 4.5h.75m0 0h.75A.75.75 0 0 1 4.5 6v.75m0 0v.75A.75.75 0 0 1 3.75 8.25h-.75m0 0H3A.75.75 0 0 1 2.25 7.5v-.75m0 0v-.75A.75.75 0 0 1 3 5.25h.75M15 11.25l1.5-1.5.75.75-1.5 1.5.75.75 1.5-1.5.75.75-1.5 1.5.75.75 1.5-1.5.75.75-1.5 1.5V21m-10.5-6.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75m0 0v.75c0 .414.336.75.75.75h.75a.75.75 0 0 0 .75-.75v-.75m0 0h.75a.75.75 0 0 0 .75-.75v-.75a.75.75 0 0 0-.75-.75h-.75m0 0h-.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75m0 0v.75c0 .414.336.75.75.75h.75a.75.75 0 0 0 .75-.75v-.75m0 0h.75a.75.75 0 0 0 .75-.75v-.75a.75.75 0 0 0-.75-.75h-.75m0 0h-.75a.75.75 0 0 0-.75.75v.75c0 .414.336.75.75.75h.75M4.5 19.5h.008v.008H4.5v-.008Z" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium text-gray-500">Custo Médio / KM</h3>
                    
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                        class="relative">
                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <div x-show="open" x-transition
                            class="absolute z-10 bottom-full left-1/2 -translate-x-1/2 mb-2 w-72 p-2 text-xs text-center text-white bg-gray-700 rounded-lg shadow-lg">
                            Cálculo: (Custo Combustível Mês) / (KM Rodado Mês)
                            <br>
                            <span class="font-mono">{{ $memoriaCalculoKm ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <p class="mt-1 text-2xl font-bold text-gray-900">R$
                    {{ number_format($custoMedioKm ?? 0, 2, ',', '.') }}</p>
            </div> 
        </div>
    </div>--}}

    {{-- Serviço Mais Frequente (Últimos 30 dias) --}}
    <button type="button" @click="$dispatch('open-modal', 'ranking-servicos')"
        class="w-full p-4 bg-white rounded-lg shadow hover:shadow-md transition group text-left">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-teal-100 rounded-full">
                <svg class="w-6 h-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Serviço Frequente (30d)</h3>
                <p class="mt-1 text-lg font-bold text-gray-900 truncate"
                    title="{{ $servicoMaisFrequente->ser_nome ?? 'Nenhum' }}">
                    {{ $servicoMaisFrequente->ser_nome ?? 'Nenhum' }}
                    @if ($servicoMaisFrequente)
                        <span
                            class="text-sm font-normal text-gray-500">({{ $servicoMaisFrequente->total }}x)</span>
                    @endif
                </p>
            </div>
        </div>
    </button>
    
    {{-- Placeholder Vazio (para alinhar a grade de 3) --}}
    <div></div>
</div>

{{-- Cards "Top Fornecedor por Tipo" --}}
{{-- Assumindo que o ENUM em fornecedores.for_tipo foi atualizado para estes valores --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-4">
    @php
        $tiposFornecedor = [
            'mecanica' => ['nome' => 'Mecânica', 'cor' => 'gray', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.83-5.83M11.42 15.17l.47-.47M11.42 15.17l-4.71 4.71M11.42 15.17l-4.71-4.71M11.42 15.17l4.71-4.71M11.42 15.17l4.71 4.71m-4.71-4.71L6.71 10.46M6.71 10.46l-4.71 4.71M6.71 10.46l4.71 4.71M6.71 10.46l-4.71-4.71M6.71 10.46l4.71-4.71" />'],
            'combustiveis' => ['nome' => 'Combustível', 'cor' => 'yellow', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-3.867 8.21 8.21 0 0 0 3 2.48Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 3.75 3.75 0 0 0-1.983 6.963 3.75 3.75 0 0 0 1.488.505Z" />'],
            'operacionais' => ['nome' => 'Operacional', 'cor' => 'blue', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />'],
            'gestao' => ['nome' => 'Gestão', 'cor' => 'purple', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V17.25m12-12v3.375c0 .621-.504 1.125-1.125 1.125h-9.75A1.125 1.125 0 0 1 3.375 8.625V5.25m12 0v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V5.25m12 0v3.375" />'],
            'outro' => ['nome' => 'Outro', 'cor' => 'pink', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />']
        ];
    @endphp

    @foreach ($tiposFornecedor as $tipo => $data)
        @if (!empty($topFornecedoresPorTipo[$tipo]))
            <div
                class="p-3 bg-white rounded-lg shadow group text-left border-l-4 border-{{ $data['cor'] }}-500">
                <div class="flex items-center space-x-2">
                    <div class="p-1.5 bg-{{ $data['cor'] }}-100 rounded-full">
                        <svg class="w-5 h-5 text-{{ $data['cor'] }}-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            {!! $data['svg'] !!}
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-medium text-gray-500">Top {{ $data['nome'] }} (Mês)</h3>
                        <p class="text-sm font-bold text-gray-900 truncate"
                            title="{{ $topFornecedoresPorTipo[$tipo]->for_nome_fantasia }}">
                            {{ $topFornecedoresPorTipo[$tipo]->for_nome_fantasia }}
                            <span
                                class="text-xs font-normal text-gray-500">({{ $topFornecedoresPorTipo[$tipo]->uso_total }}x)</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

