@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção!</p>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-8">
    {{-- Seção de Dados Pessoais --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Pessoais</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <label for="mot_nome" class="block font-medium text-sm text-gray-700">Nome Completo*</label>
                <input type="text" name="mot_nome" id="mot_nome" class="mt-1 block w-full" value="{{ old('mot_nome', $motorista->mot_nome) }}" required>
            </div>
            <div>
                <label for="mot_apelido" class="block font-medium text-sm text-gray-700">Apelido</label>
                <input type="text" name="mot_apelido" id="mot_apelido" class="mt-1 block w-full" value="{{ old('mot_apelido', $motorista->mot_apelido) }}">
            </div>
            <div>
                <label for="mot_data_nascimento" class="block font-medium text-sm text-gray-700">Data de Nascimento</label>
                <input type="date" name="mot_data_nascimento" id="mot_data_nascimento" class="mt-1 block w-full" value="{{ old('mot_data_nascimento', optional($motorista->mot_data_nascimento)->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="mot_cpf" class="block font-medium text-sm text-gray-700">CPF</label>
                <input type="text" name="mot_cpf" id="mot_cpf" class="mt-1 block w-full" value="{{ old('mot_cpf', $motorista->mot_cpf) }}">
            </div>
             <div>
                <label for="mot_status" class="block font-medium text-sm text-gray-700">Status</label>
                <select name="mot_status" id="mot_status" class="mt-1 block w-full">
                    <option value="1" @selected(old('mot_status', $motorista->mot_status) == 1)>Ativo</option>
                    <option value="0" @selected(old('mot_status', $motorista->mot_status) == 0)>Inativo</option>
                </select>
            </div>
        </div>
    </div>
    
    {{-- Seção de Contato --}}
    <div class="form-section">
        <h3 class="form-section-title">Contato</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <div>
                <label for="mot_telefone1" class="block font-medium text-sm text-gray-700">Telefone 1</label>
                <input type="text" name="mot_telefone1" id="mot_telefone1" class="mt-1 block w-full" value="{{ old('mot_telefone1', $motorista->mot_telefone1) }}">
            </div>
             <div>
                <label for="mot_telefone2" class="block font-medium text-sm text-gray-700">Telefone 2</label>
                <input type="text" name="mot_telefone2" id="mot_telefone2" class="mt-1 block w-full" value="{{ old('mot_telefone2', $motorista->mot_telefone2) }}">
            </div>
             <div>
                <label for="mot_email" class="block font-medium text-sm text-gray-700">Email</label>
                <input type="email" name="mot_email" id="mot_email" class="mt-1 block w-full" value="{{ old('mot_email', $motorista->mot_email) }}">
            </div>
        </div>
    </div>

    {{-- Seção CNH --}}
    <div class="form-section">
        <h3 class="form-section-title">Carteira Nacional de Habilitação (CNH)</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="mot_cnh_numero" class="block font-medium text-sm text-gray-700">Número da CNH</label>
                <input type="text" name="mot_cnh_numero" id="mot_cnh_numero" class="mt-1 block w-full" value="{{ old('mot_cnh_numero', $motorista->mot_cnh_numero) }}">
            </div>
            <div>
                <label for="mot_cnh_categoria" class="block font-medium text-sm text-gray-700">Categoria</label>
                <input type="text" name="mot_cnh_categoria" id="mot_cnh_categoria" class="mt-1 block w-full" value="{{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) }}">
            </div>
            <div>
                <label for="mot_cnh_data_validade" class="block font-medium text-sm text-gray-700">Data de Validade</label>
                <input type="date" name="mot_cnh_data_validade" id="mot_cnh_data_validade" class="mt-1 block w-full" value="{{ old('mot_cnh_data_validade', optional($motorista->mot_cnh_data_validade)->format('Y-m-d')) }}">
            </div>
        </div>
    </div>
    
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('motoristas.index') }}" class="btn-secondary mr-4">Cancelar</a>
    <button type="submit" class="btn-primary">Salvar Motorista</button>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $('#mot_cpf').mask('000.000.000-00', {reverse: true});
        
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('#mot_telefone1').mask(SPMaskBehavior, spOptions);
        $('#mot_telefone2').mask(SPMaskBehavior, spOptions);
    });
</script>
@endpush
