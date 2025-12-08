<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes da Apólice</h3>
    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Número da Apólice</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_numero }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Tipo de Seguro</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_tipo ?? '-' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
            <dd class="mt-1 text-sm text-gray-900">R$ {{ number_format($apolice->seg_valor_total, 2, ',', '.') }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Parcelas</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_parcelas ?? '1' }}</dd>
        </div>
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Franquia</dt>
            <dd class="mt-1 text-sm text-gray-900">R$ {{ number_format($apolice->seg_franquia, 2, ',', '.') }}</dd>
        </div>

        @if($apolice->seg_arquivo)
        <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Documento da Apólice</dt>
            <dd class="mt-1 text-sm text-gray-900">
                <a href="{{ route('seguros.download', $apolice->seg_id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Baixar Arquivo
                </a>
            </dd>
        </div>
        @endif

        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Observações</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $apolice->seg_obs ?? 'Nenhuma observação.' }}</dd>
        </div>
    </dl>
</div>
