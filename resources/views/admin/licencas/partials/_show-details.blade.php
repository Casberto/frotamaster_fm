<div class="space-y-6">
    {{-- Estatísticas / Resumo --}}
    <div class="grid grid-cols-2 gap-4">
        {{-- Status --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center">
            @php
                $statusColor = match($licenca->status) {
                    'ativo' => 'text-green-600',
                    'expirado' => 'text-red-500',
                    'pendente' => 'text-yellow-600',
                     default => 'text-gray-500'
                };
                $daysLeft = \Carbon\Carbon::now()->diffInDays($licenca->data_vencimento, false);
            @endphp
            <span class="text-xl font-bold uppercase {{ $statusColor }}">{{ $licenca->status }}</span>
            <span class="text-xs text-gray-500 mt-1">Status Atual</span>
        </div>
        
        {{-- Dias Restantes --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center">
            <span class="text-xl font-bold {{ $daysLeft > 0 ? 'text-gray-800' : 'text-red-500' }}">
                {{ $daysLeft > 0 ? (int)$daysLeft : 'Vencida' }}
            </span>
            <span class="text-xs text-gray-500 mt-1">{{ $daysLeft > 0 ? 'Dias Restantes' : 'Expirada' }}</span>
        </div>
    </div>

    {{-- Info do Plano --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
             <h3 class="font-semibold text-gray-800">Detalhes do Plano</h3>
             <span class="px-2 py-0.5 text-xs font-bold bg-blue-100 text-blue-700 rounded-lg uppercase">{{ $licenca->plano }}</span>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                <span class="text-sm text-gray-600">Data de Início</span>
                <span class="text-sm font-medium text-gray-900">{{ $licenca->data_inicio->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                <span class="text-sm text-gray-600">Data de Vencimento</span>
                <span class="text-sm font-medium text-gray-900">{{ $licenca->data_vencimento->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Valor Pago</span>
                <span class="text-sm font-medium text-gray-900">R$ {{ number_format($licenca->valor_pago, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>
    
    {{-- Info de Auditoria --}}
    <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-500 space-y-1">
        <p>Criado por: <strong>{{ $licenca->criador->name ?? 'Sistema' }}</strong></p>
        <p>Criado em: {{ $licenca->created_at->format('d/m/Y H:i') }}</p>
        <p>Última atualização: {{ $licenca->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
