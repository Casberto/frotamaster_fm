@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Aviso</p>
        <p>{{ session('warning') }}</p>
    </div>
@endif


<div class="space-y-8">
    {{-- Seção de Dados da Manutenção --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados da Manutenção</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="id_veiculo" class="block font-medium text-sm text-gray-700">Veículo*</label>
                <select name="id_veiculo" id="id_veiculo" class="mt-1 block w-full" required>
                    @foreach($veiculos as $veiculo)
                        <option value="{{ $veiculo->id }}" @selected(old('id_veiculo', $manutencao->id_veiculo ?? '') == $veiculo->id)>
                            {{ $veiculo->placa }} - {{ $veiculo->marca }} {{ $veiculo->modelo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tipo_manutencao" class="block font-medium text-sm text-gray-700">Tipo de Manutenção*</label>
                <select name="tipo_manutencao" id="tipo_manutencao" class="mt-1 block w-full" required>
                    <option value="preventiva" @selected(old('tipo_manutencao', $manutencao->tipo_manutencao ?? '') == 'preventiva')>Preventiva</option>
                    <option value="corretiva" @selected(old('tipo_manutencao', $manutencao->tipo_manutencao ?? '') == 'corretiva')>Corretiva</option>
                    <option value="preditiva" @selected(old('tipo_manutencao', $manutencao->tipo_manutencao ?? '') == 'preditiva')>Preditiva</option>
                    <option value="outra" @selected(old('tipo_manutencao', $manutencao->tipo_manutencao ?? '') == 'outra')>Outra</option>
                </select>
            </div>
            <div>
                <label for="data_manutencao" class="block font-medium text-sm text-gray-700">Data*</label>
                <input type="date" name="data_manutencao" id="data_manutencao" class="mt-1 block w-full" value="{{ old('data_manutencao', isset($manutencao->data_manutencao) ? \Carbon\Carbon::parse($manutencao->data_manutencao)->format('Y-m-d') : '') }}" required>
            </div>
        </div>
        <div class="mt-6">
            <label for="descricao_servico" class="block font-medium text-sm text-gray-700">Descrição do Serviço*</label>
            <input type="text" name="descricao_servico" id="descricao_servico" class="mt-1 block w-full" value="{{ old('descricao_servico', $manutencao->descricao_servico ?? '') }}" required>
        </div>
    </div>

    {{-- Seção de Custos e Prazos --}}
    <div class="form-section">
        <h3 class="form-section-title">Custos e Prazos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="quilometragem" class="block font-medium text-sm text-gray-700">Quilometragem*</label>
                <input type="text" name="quilometragem" id="quilometragem" class="mt-1 block w-full" value="{{ old('quilometragem', $manutencao->quilometragem ?? '') }}" required>
            </div>
            <div>
                <label for="custo_previsto" class="block font-medium text-sm text-gray-700">Custo Previsto (R$)</label>
                <input type="text" name="custo_previsto" id="custo_previsto" class="mt-1 block w-full" value="{{ old('custo_previsto', $manutencao->custo_previsto ?? '') }}">
            </div>
            <div>
                <label for="custo_total" class="block font-medium text-sm text-gray-700">Custo Total (R$)*</label>
                <input type="text" name="custo_total" id="custo_total" class="mt-1 block w-full" value="{{ old('custo_total', $manutencao->custo_total ?? '') }}" required>
            </div>
            <div>
                <label for="nome_fornecedor" class="block font-medium text-sm text-gray-700">Fornecedor</label>
                <input type="text" name="nome_fornecedor" id="nome_fornecedor" class="mt-1 block w-full" value="{{ old('nome_fornecedor', $manutencao->nome_fornecedor ?? '') }}">
            </div>
             <div>
                <label for="responsavel" class="block font-medium text-sm text-gray-700">Responsável</label>
                <input type="text" name="responsavel" id="responsavel" class="mt-1 block w-full" value="{{ old('responsavel', $manutencao->responsavel ?? '') }}">
            </div>
            <div>
                <label for="status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="status" id="status" class="mt-1 block w-full" required>
                    <option value="agendada" @selected(old('status', $manutencao->status ?? 'agendada') == 'agendada')>Agendada</option>
                    <option value="em_andamento" @selected(old('status', $manutencao->status ?? '') == 'em_andamento')>Em Andamento</option>
                    <option value="concluida" @selected(old('status', $manutencao->status ?? '') == 'concluida')>Concluída</option>
                    <option value="cancelada" @selected(old('status', $manutencao->status ?? '') == 'cancelada')>Cancelada</option>
                </select>
            </div>
        </div>
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="proxima_revisao_data" class="block font-medium text-sm text-gray-700">Próxima Revisão (Data)</label>
                <input type="date" name="proxima_revisao_data" id="proxima_revisao_data" class="mt-1 block w-full" value="{{ old('proxima_revisao_data', isset($manutencao->proxima_revisao_data) ? \Carbon\Carbon::parse($manutencao->proxima_revisao_data)->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label for="proxima_revisao_km" class="block font-medium text-sm text-gray-700">Próxima Revisão (KM)</label>
                <input type="text" name="proxima_revisao_km" id="proxima_revisao_km" class="mt-1 block w-full" value="{{ old('proxima_revisao_km', $manutencao->proxima_revisao_km ?? '') }}">
            </div>
        </div>
        <div class="mt-6">
            <label for="observacoes" class="block font-medium text-sm text-gray-700">Observações</label>
            <textarea name="observacoes" id="observacoes" rows="3" class="mt-1 block w-full">{{ old('observacoes', $manutencao->observacoes ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('manutencoes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Cancelar</a>
    <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Salvar</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscaras
        $('#quilometragem').mask('000.000.000', {reverse: true});
        $('#proxima_revisao_km').mask('000.000.000', {reverse: true});
        $('#custo_total').mask('000.000.000,00', {reverse: true});
        $('#custo_previsto').mask('000.000.000,00', {reverse: true});
    });
</script>
