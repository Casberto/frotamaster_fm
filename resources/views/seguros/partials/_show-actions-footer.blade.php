<div class="flex justify-between items-center bg-gray-50 px-6 py-4">
    <div class="text-sm text-gray-500">
        Criado em {{ $apolice->created_at ? $apolice->created_at->format('d/m/Y H:i') : '-' }}
    </div>
    <div class="flex space-x-3">
        <form action="{{ route('seguros.renew', $apolice->seg_id) }}" method="POST" onsubmit="return confirm('Deseja gerar uma renovação desta apólice?');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm font-medium focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Renovar
            </button>
        </form>
        <a href="{{ route('seguros.edit', $apolice->seg_id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm font-medium focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Editar
        </a>
    </div>
</div>
