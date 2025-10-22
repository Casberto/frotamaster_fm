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
    {{-- Seção de Dados Pessoais e Contato --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Pessoais e Contato</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- mot_nome (Obrigatório sempre) --}}
            <div class="md:col-span-2">
                <label for="mot_nome" class="block font-medium text-sm text-gray-700">Nome Completo*</label>
                <input type="text" name="mot_nome" id="mot_nome" class="mt-1 block w-full" value="{{ old('mot_nome', $motorista->mot_nome) }}" required>
            </div>

            {{-- mot_status (Obrigatório sempre) --}}
            <div>
                <label for="mot_status" class="block font-medium text-sm text-gray-700">Status*</label>
                <select name="mot_status" id="mot_status" class="mt-1 block w-full">
                    <option value="">Selecione...</option>
                    <option value="Ativo" {{ old('mot_status', $motorista->mot_status) == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="Inativo" {{ old('mot_status', $motorista->mot_status) == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                    <option value="Bloqueado" {{ old('mot_status', $motorista->mot_status) == 'Bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                    <option value="Em treinamento" {{ old('mot_status', $motorista->mot_status) == 'Em treinamento' ? 'selected' : '' }}>Em treinamento</option>
                    <option value="Afastado" {{ old('mot_status', $motorista->mot_status) == 'Afastado' ? 'selected' : '' }}>Afastado</option>
                    <option value="Aguardando documentação" {{ old('mot_status', $motorista->mot_status) == 'Aguardando documentação' ? 'selected' : '' }}>Aguardando documentação</option>
                    <option value="Suspenso" {{ old('mot_status', $motorista->mot_status) == 'Suspenso' ? 'selected' : '' }}>Suspenso</option>
                    <option value="Rejeitado" {{ old('mot_status', $motorista->mot_status) == 'Rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                    <option value="Em análise" {{ old('mot_status', $motorista->mot_status) == 'Em análise' ? 'selected' : '' }}>Em análise</option>
                </select>
            </div>

            {{-- Chave de configuração: 'usar_usuario' --}}
            @if($configuracoes['usar_usuario'] ?? false)
            <div>
                <label for="mot_user_id" class="block font-medium text-sm text-gray-700">Vincular Usuário*</label>
                <select name="mot_user_id" id="mot_user_id" class="mt-1 block w-full">
                    <option value="">Selecione um usuário</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('mot_user_id', $motorista->mot_user_id) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Chave de configuração: 'usar_apelido' --}}
            @if($configuracoes['usar_apelido'] ?? false)
            <div>
                <label for="mot_apelido" class="block font-medium text-sm text-gray-700">Apelido*</label>
                <input type="text" name="mot_apelido" id="mot_apelido" class="mt-1 block w-full" value="{{ old('mot_apelido', $motorista->mot_apelido) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_data_nascimento' --}}
            @if($configuracoes['usar_data_nascimento'] ?? false)
            <div>
                <label for="mot_data_nascimento" class="block font-medium text-sm text-gray-700">Data de Nascimento*</label>
                <input type="date" name="mot_data_nascimento" id="mot_data_nascimento" class="mt-1 block w-full" value="{{ old('mot_data_nascimento', optional($motorista->mot_data_nascimento)->format('Y-m-d')) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_genero' --}}
            @if($configuracoes['usar_genero'] ?? false)
            <div>
                <label for="mot_genero" class="block font-medium text-sm text-gray-700">Gênero*</label>
                <select name="mot_genero" id="mot_genero" class="mt-1 block w-full">
                    <option value="Masculino" {{ old('mot_genero', $motorista->mot_genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Feminino" {{ old('mot_genero', $motorista->mot_genero) == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                    <option value="Outro" {{ old('mot_genero', $motorista->mot_genero) == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>
            @endif

            {{-- Chave de configuração: 'usar_nacionalidade' --}}
            @if($configuracoes['usar_nacionalidade'] ?? false)
            <div>
                <label for="mot_nacionalidade" class="block font-medium text-sm text-gray-700">Nacionalidade*</label>
                <input type="text" name="mot_nacionalidade" id="mot_nacionalidade" class="mt-1 block w-full" value="{{ old('mot_nacionalidade', $motorista->mot_nacionalidade) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_estado_civil' --}}
            @if($configuracoes['usar_estado_civil'] ?? false)
            <div>
                <label for="mot_estado_civil" class="block font-medium text-sm text-gray-700">Estado Civil*</label>
                <select name="mot_estado_civil" id="mot_estado_civil" class="mt-1 block w-full">
                    <option value="Solteiro(a)" {{ old('mot_estado_civil', $motorista->mot_estado_civil) == 'Solteiro(a)' ? 'selected' : '' }}>Solteiro(a)</option>
                    <option value="Casado(a)" {{ old('mot_estado_civil', $motorista->mot_estado_civil) == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                    <option value="Divorciado(a)" {{ old('mot_estado_civil', $motorista->mot_estado_civil) == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                    <option value="Viúvo(a)" {{ old('mot_estado_civil', $motorista->mot_estado_civil) == 'Viúvo(a)' ? 'selected' : '' }}>Viúvo(a)</option>
                    <option value="Outro" {{ old('mot_estado_civil', $motorista->mot_estado_civil) == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>
            @endif

            {{-- Chave de configuração: 'usar_nome_mae' --}}
            @if($configuracoes['usar_nome_mae'] ?? false)
            <div class="md:col-span-2">
                <label for="mot_nome_mae" class="block font-medium text-sm text-gray-700">Nome da Mãe*</label>
                <input type="text" name="mot_nome_mae" id="mot_nome_mae" class="mt-1 block w-full" value="{{ old('mot_nome_mae', $motorista->mot_nome_mae) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_nome_pai' --}}
            @if($configuracoes['usar_nome_pai'] ?? false)
            <div class="md:col-span-2">
                <label for="mot_nome_pai" class="block font-medium text-sm text-gray-700">Nome do Pai*</label>
                <input type="text" name="mot_nome_pai" id="mot_nome_pai" class="mt-1 block w-full" value="{{ old('mot_nome_pai', $motorista->mot_nome_pai) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_email' --}}
            @if($configuracoes['usar_email'] ?? false)
             <div>
                <label for="mot_email" class="block font-medium text-sm text-gray-700">Email*</label>
                <input type="email" name="mot_email" id="mot_email" class="mt-1 block w-full" value="{{ old('mot_email', $motorista->mot_email) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_telefone1' --}}
            @if($configuracoes['usar_telefone1'] ?? false)
             <div>
                <label for="mot_telefone1" class="block font-medium text-sm text-gray-700">Telefone 1*</label>
                <input type="text" name="mot_telefone1" id="mot_telefone1" class="mt-1 block w-full" value="{{ old('mot_telefone1', $motorista->mot_telefone1) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_telefone2' --}}
            @if($configuracoes['usar_telefone2'] ?? false)
             <div>
                <label for="mot_telefone2" class="block font-medium text-sm text-gray-700">Telefone 2*</label>
                <input type="text" name="mot_telefone2" id="mot_telefone2" class="mt-1 block w-full" value="{{ old('mot_telefone2', $motorista->mot_telefone2) }}">
            </div>
            @endif
        </div>
    </div>
    
    {{-- Seção de Documentos --}}
    <div class="form-section">
        <h3 class="form-section-title">Documentos</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Chave de configuração: 'usar_cpf' --}}
            @if($configuracoes['usar_cpf'] ?? false)
            <div>
                <label for="mot_cpf" class="block font-medium text-sm text-gray-700">CPF*</label>
                <input type="text" name="mot_cpf" id="mot_cpf" class="mt-1 block w-full" value="{{ old('mot_cpf', $motorista->mot_cpf) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_rg' --}}
            @if($configuracoes['usar_rg'] ?? false)
            <div>
                <label for="mot_rg" class="block font-medium text-sm text-gray-700">RG*</label>
                <input type="text" name="mot_rg" id="mot_rg" class="mt-1 block w-full" value="{{ old('mot_rg', $motorista->mot_rg) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_orgao_emissor_rg' --}}
            @if($configuracoes['usar_orgao_emissor_rg'] ?? false)
            <div>
                <label for="mot_orgao_emissor_rg" class="block font-medium text-sm text-gray-700">Órgão Emissor*</label>
                <input type="text" name="mot_orgao_emissor_rg" id="mot_orgao_emissor_rg" class="mt-1 block w-full" value="{{ old('mot_orgao_emissor_rg', $motorista->mot_orgao_emissor_rg) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_data_emissao_rg' --}}
            @if($configuracoes['usar_data_emissao_rg'] ?? false)
            <div>
                <label for="mot_data_emissao_rg" class="block font-medium text-sm text-gray-700">Data de Emissão RG*</label>
                <input type="date" name="mot_data_emissao_rg" id="mot_data_emissao_rg" class="mt-1 block w-full" value="{{ old('mot_data_emissao_rg', optional($motorista->mot_data_emissao_rg)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_pis' --}}
            @if($configuracoes['usar_pis'] ?? false)
            <div>
                <label for="mot_pis" class="block font-medium text-sm text-gray-700">PIS*</label>
                <input type="text" name="mot_pis" id="mot_pis" class="mt-1 block w-full" value="{{ old('mot_pis', $motorista->mot_pis) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_ctps_numero' --}}
            @if($configuracoes['usar_ctps_numero'] ?? false)
            <div>
                <label for="mot_ctps_numero" class="block font-medium text-sm text-gray-700">Nº CTPS*</label>
                <input type="text" name="mot_ctps_numero" id="mot_ctps_numero" class="mt-1 block w-full" value="{{ old('mot_ctps_numero', $motorista->mot_ctps_numero) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_ctps_serie' --}}
            @if($configuracoes['usar_ctps_serie'] ?? false)
            <div>
                <label for="mot_ctps_serie" class="block font-medium text-sm text-gray-700">Série CTPS*</label>
                <input type="text" name="mot_ctps_serie" id="mot_ctps_serie" class="mt-1 block w-full" value="{{ old('mot_ctps_serie', $motorista->mot_ctps_serie) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_titulo_eleitor' --}}
            @if($configuracoes['usar_titulo_eleitor'] ?? false)
            <div>
                <label for="mot_titulo_eleitor" class="block font-medium text-sm text-gray-700">Título de Eleitor*</label>
                <input type="text" name="mot_titulo_eleitor" id="mot_titulo_eleitor" class="mt-1 block w-full" value="{{ old('mot_titulo_eleitor', $motorista->mot_titulo_eleitor) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_zona_eleitoral' --}}
            @if($configuracoes['usar_zona_eleitoral'] ?? false)
            <div>
                <label for="mot_zona_eleitoral" class="block font-medium text-sm text-gray-700">Zona*</label>
                <input type="text" name="mot_zona_eleitoral" id="mot_zona_eleitoral" class="mt-1 block w-full" value="{{ old('mot_zona_eleitoral', $motorista->mot_zona_eleitoral) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_secao_eleitoral' --}}
            @if($configuracoes['usar_secao_eleitoral'] ?? false)
            <div>
                <label for="mot_secao_eleitoral" class="block font-medium text-sm text-gray-700">Seção*</label>
                <input type="text" name="mot_secao_eleitoral" id="mot_secao_eleitoral" class="mt-1 block w-full" value="{{ old('mot_secao_eleitoral', $motorista->mot_secao_eleitoral) }}">
            </div>
            @endif
        </div>
    </div>

    {{-- Seção CNH --}}
    @if($configuracoes['exige_cnh'] ?? false)
    <div class="form-section">
        <h3 class="form-section-title">Carteira Nacional de Habilitação (CNH)</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Chave de configuração: 'usar_cnh_numero' --}}
            @if($configuracoes['usar_cnh_numero'] ?? false)
            <div>
                <label for="mot_cnh_numero" class="block font-medium text-sm text-gray-700">Número da CNH*</label>
                <input type="text" name="mot_cnh_numero" id="mot_cnh_numero" class="mt-1 block w-full" value="{{ old('mot_cnh_numero', $motorista->mot_cnh_numero) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_cnh_categoria' --}}
            @if($configuracoes['usar_cnh_categoria'] ?? false)
            <div>
                <label for="mot_cnh_categoria" class="block font-medium text-sm text-gray-700">Categoria*</label>
                <select name="mot_cnh_categoria" id="mot_cnh_categoria" class="mt-1 block w-full">
                    <option value="A" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'A' ? 'selected' : '' }}>A – Veículos de duas ou três rodas (moto)</option>
                    <option value="B" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'B' ? 'selected' : '' }}>B – Veículos de até 3.500 kg e até 8 passageiros</option>
                    <option value="C" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'C' ? 'selected' : '' }}>C – Veículos de carga acima de 3.500 kg</option>
                    <option value="D" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'D' ? 'selected' : '' }}>D – Veículos para transporte de mais de 8 passageiros</option>
                    <option value="E" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'E' ? 'selected' : '' }}>E – Combinação de veículos (carreta, caminhão-trator)</option>
                    <option value="AB" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'AB' ? 'selected' : '' }}>AB – Motos e veículos leves</option>
                    <option value="AC" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'AC' ? 'selected' : '' }}>AC – Motos e veículos de carga</option>
                    <option value="AD" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'AD' ? 'selected' : '' }}>AD – Motos e transporte de passageiros</option>
                    <option value="AE" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'AE' ? 'selected' : '' }}>AE – Motos e combinação de veículos</option>
                    <option value="Outro" {{ old('mot_cnh_categoria', $motorista->mot_cnh_categoria) == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_cnh_data_emissao' --}}
            @if($configuracoes['usar_cnh_data_emissao'] ?? false)
            <div>
                <label for="mot_cnh_data_emissao" class="block font-medium text-sm text-gray-700">Data de Emissão*</label>
                <input type="date" name="mot_cnh_data_emissao" id="mot_cnh_data_emissao" class="mt-1 block w-full" value="{{ old('mot_cnh_data_emissao', optional($motorista->mot_cnh_data_emissao)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_cnh_data_validade' --}}
            @if($configuracoes['usar_cnh_data_validade'] ?? false)
            <div>
                <label for="mot_cnh_data_validade" class="block font-medium text-sm text-gray-700">Data de Validade*</label>
                <input type="date" name="mot_cnh_data_validade" id="mot_cnh_data_validade" class="mt-1 block w-full" value="{{ old('mot_cnh_data_validade', optional($motorista->mot_cnh_data_validade)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_cnh_primeira_habilitacao' --}}
            @if($configuracoes['usar_cnh_primeira_habilitacao'] ?? false)
            <div>
                <label for="mot_cnh_primeira_habilitacao" class="block font-medium text-sm text-gray-700">Primeira Habilitação*</label>
                <input type="date" name="mot_cnh_primeira_habilitacao" id="mot_cnh_primeira_habilitacao" class="mt-1 block w-full" value="{{ old('mot_cnh_primeira_habilitacao', optional($motorista->mot_cnh_primeira_habilitacao)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_cnh_uf' --}}
            @if($configuracoes['usar_cnh_uf'] ?? false)
            <div>
                <label for="mot_cnh_uf" class="block font-medium text-sm text-gray-700">UF da CNH*</label>
                <select name="mot_cnh_uf" id="mot_cnh_uf" class="mt-1 block w-full">
                    <option value="">Selecione a UF</option>
                    <option value="AC" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'AC' ? 'selected' : '' }}>Acre (AC)</option>
                    <option value="AL" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'AL' ? 'selected' : '' }}>Alagoas (AL)</option>
                    <option value="AP" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'AP' ? 'selected' : '' }}>Amapá (AP)</option>
                    <option value="AM" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'AM' ? 'selected' : '' }}>Amazonas (AM)</option>
                    <option value="BA" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'BA' ? 'selected' : '' }}>Bahia (BA)</option>
                    <option value="CE" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'CE' ? 'selected' : '' }}>Ceará (CE)</option>
                    <option value="DF" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'DF' ? 'selected' : '' }}>Distrito Federal (DF)</option>
                    <option value="ES" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'ES' ? 'selected' : '' }}>Espírito Santo (ES)</option>
                    <option value="GO" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'GO' ? 'selected' : '' }}>Goiás (GO)</option>
                    <option value="MA" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'MA' ? 'selected' : '' }}>Maranhão (MA)</option>
                    <option value="MT" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'MT' ? 'selected' : '' }}>Mato Grosso (MT)</option>
                    <option value="MS" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul (MS)</option>
                    <option value="MG" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'MG' ? 'selected' : '' }}>Minas Gerais (MG)</option>
                    <option value="PA" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'PA' ? 'selected' : '' }}>Pará (PA)</option>
                    <option value="PB" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'PB' ? 'selected' : '' }}>Paraíba (PB)</option>
                    <option value="PR" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'PR' ? 'selected' : '' }}>Paraná (PR)</option>
                    <option value="PE" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'PE' ? 'selected' : '' }}>Pernambuco (PE)</option>
                    <option value="PI" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'PI' ? 'selected' : '' }}>Piauí (PI)</option>
                    <option value="RJ" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro (RJ)</option>
                    <option value="RN" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte (RN)</option>
                    <option value="RS" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul (RS)</option>
                    <option value="RO" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'RO' ? 'selected' : '' }}>Rondônia (RO)</option>
                    <option value="RR" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'RR' ? 'selected' : '' }}>Roraima (RR)</option>
                    <option value="SC" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'SC' ? 'selected' : '' }}>Santa Catarina (SC)</option>
                    <option value="SP" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'SP' ? 'selected' : '' }}>São Paulo (SP)</option>
                    <option value="SE" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'SE' ? 'selected' : '' }}>Sergipe (SE)</option>
                    <option value="TO" {{ old('mot_cnh_uf', $motorista->mot_cnh_uf) == 'TO' ? 'selected' : '' }}>Tocantins (TO)</option>
                </select>
            </div>
            @endif

            {{-- Chave de configuração: 'usar_cnh_observacoes' --}}
            @if($configuracoes['usar_cnh_observacoes'] ?? false)
            <div class="md:col-span-4">
                <label for="mot_cnh_observacoes" class="block font-medium text-sm text-gray-700">Observações da CNH</label>
                <textarea name="mot_cnh_observacoes" id="mot_cnh_observacoes" rows="2" class="mt-1 block w-full">{{ old('mot_cnh_observacoes', $motorista->mot_cnh_observacoes) }}</textarea>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    {{-- Seção de Endereço --}}
    <div class="form-section">
        <h3 class="form-section-title">Endereço</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Chave de configuração: 'usar_cep' --}}
            @if($configuracoes['usar_cep'] ?? false)
            <div>
                <label for="mot_cep" class="block font-medium text-sm text-gray-700">CEP*</label>
                <input type="text" name="mot_cep" id="mot_cep" class="mt-1 block w-full" value="{{ old('mot_cep', $motorista->mot_cep) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_endereco' --}}
            @if($configuracoes['usar_endereco'] ?? false)
            <div class="md:col-span-3">
                <label for="mot_endereco" class="block font-medium text-sm text-gray-700">Endereço*</label>
                <input type="text" name="mot_endereco" id="mot_endereco" class="mt-1 block w-full" value="{{ old('mot_endereco', $motorista->mot_endereco) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_numero' --}}
            @if($configuracoes['usar_numero'] ?? false)
            <div>
                <label for="mot_numero" class="block font-medium text-sm text-gray-700">Número</label>
                <input type="text" name="mot_numero" id="mot_numero" class="mt-1 block w-full" value="{{ old('mot_numero', $motorista->mot_numero) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_complemento' --}}
            @if($configuracoes['usar_complemento'] ?? false)
            <div>
                <label for="mot_complemento" class="block font-medium text-sm text-gray-700">Complemento</label>
                <input type="text" name="mot_complemento" id="mot_complemento" class="mt-1 block w-full" value="{{ old('mot_complemento', $motorista->mot_complemento) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_bairro' --}}
            @if($configuracoes['usar_bairro'] ?? false)
            <div>
                <label for="mot_bairro" class="block font-medium text-sm text-gray-700">Bairro*</label>
                <input type="text" name="mot_bairro" id="mot_bairro" class="mt-1 block w-full" value="{{ old('mot_bairro', $motorista->mot_bairro) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_cidade' --}}
            @if($configuracoes['usar_cidade'] ?? false)
            <div>
                <label for="mot_cidade" class="block font-medium text-sm text-gray-700">Cidade*</label>
                <input type="text" name="mot_cidade" id="mot_cidade" class="mt-1 block w-full" value="{{ old('mot_cidade', $motorista->mot_cidade) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_estado' --}}
            @if($configuracoes['usar_estado'] ?? false)
            <div>
                <label for="mot_estado" class="block font-medium text-sm text-gray-700">Estado*</label>
                <input type="text" name="mot_estado" id="mot_estado" class="mt-1 block w-full" value="{{ old('mot_estado', $motorista->mot_estado) }}">
            </div>
            @endif
        </div>
    </div>

    {{-- Seção de Dados Profissionais --}}
    @if($configuracoes['usar_data_admissao'] ?? false)
    <div class="form-section">
        <h3 class="form-section-title">Dados Profissionais</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             {{-- Chave de configuração: 'usar_data_admissao' --}}
            @if($configuracoes['usar_data_admissao'] ?? false)
            <div>
                <label for="mot_data_admissao" class="block font-medium text-sm text-gray-700">Data de Admissão*</label>
                <input type="date" name="mot_data_admissao" id="mot_data_admissao" class="mt-1 block w-full" value="{{ old('mot_data_admissao', optional($motorista->mot_data_admissao)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_data_demissao' --}}
            @if($configuracoes['usar_data_demissao'] ?? false)
            <div>
                <label for="mot_data_demissao" class="block font-medium text-sm text-gray-700">Data de Demissão</label>
                <input type="date" name="mot_data_demissao" id="mot_data_demissao" class="mt-1 block w-full" value="{{ old('mot_data_demissao', optional($motorista->mot_data_demissao)->format('Y-m-d')) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_tipo_contrato' --}}
            @if($configuracoes['usar_tipo_contrato'] ?? false)
            <div>
                
                <label for="mot_tipo_contrato" class="block font-medium text-sm text-gray-700">Tipo de Contrato*
                    <span 
                        class="text-gray-500 cursor-help" 
                        title="Selecione o tipo de vínculo do motorista com a empresa (CLT, PJ, Autônomo, etc).">
                        ?
                    </span>
                </label>
                <select name="mot_tipo_contrato" id="mot_tipo_contrato" class="mt-1 block w-full">
                    <option value="">Selecione...</option>
                    <option value="CLT" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'CLT' ? 'selected' : '' }}>CLT</option>
                    <option value="PJ" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica (PJ)</option>
                    <option value="Autônomo" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Autônomo' ? 'selected' : '' }}>Autônomo</option>
                    <option value="Temporário" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Temporário' ? 'selected' : '' }}>Temporário</option>
                    <option value="Terceirizado" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Terceirizado' ? 'selected' : '' }}>Terceirizado</option>
                    <option value="Agregado" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Agregado' ? 'selected' : '' }}>Agregado</option>
                    <option value="Cooperado" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Cooperado' ? 'selected' : '' }}>Cooperado</option>
                    <option value="Freelancer" {{ old('mot_tipo_contrato', $motorista->mot_tipo_contrato) == 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                </select>
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_categoria_profissional' --}}
            @if($configuracoes['usar_categoria_profissional'] ?? false)
            <div>
                <label for="mot_categoria_profissional" class="block font-medium text-sm text-gray-700">Categoria Profissional*</label>
                <input type="text" name="mot_categoria_profissional" id="mot_categoria_profissional" class="mt-1 block w-full" value="{{ old('mot_categoria_profissional', $motorista->mot_categoria_profissional) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_matricula_interna' --}}
            @if($configuracoes['usar_matricula_interna'] ?? false)
            <div>
                <label for="mot_matricula_interna" class="block font-medium text-sm text-gray-700">Matrícula Interna*</label>
                <input type="text" name="mot_matricula_interna" id="mot_matricula_interna" class="mt-1 block w-full" value="{{ old('mot_matricula_interna', $motorista->mot_matricula_interna) }}">
            </div>
            @endif
        </div>
    </div>
    @endif
    
    @if($configuracoes['usar_banco'] ?? false)
    {{-- Seção de Dados Bancários --}}
    <div class="form-section">
        <h3 class="form-section-title">Dados Bancários</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Chave de configuração: 'usar_banco' --}}
            @if($configuracoes['usar_banco'] ?? false)
            <div>
                <label for="mot_banco" class="block font-medium text-sm text-gray-700">Banco*</label>
                <input type="text" name="mot_banco" id="mot_banco" class="mt-1 block w-full" value="{{ old('mot_banco', $motorista->mot_banco) }}">
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_agencia' --}}
            @if($configuracoes['usar_agencia'] ?? false)
            <div>
                <label for="mot_agencia" class="block font-medium text-sm text-gray-700">Agência*</label>
                <input type="text" name="mot_agencia" id="mot_agencia" class="mt-1 block w-full" value="{{ old('mot_agencia', $motorista->mot_agencia) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_conta' --}}
            @if($configuracoes['usar_conta'] ?? false)
            <div>
                <label for="mot_conta" class="block font-medium text-sm text-gray-700">Conta*</label>
                <input type="text" name="mot_conta" id="mot_conta" class="mt-1 block w-full" value="{{ old('mot_conta', $motorista->mot_conta) }}">
            </div>
            @endif

            {{-- Chave de configuração: 'usar_tipo_conta' --}}
            @if($configuracoes['usar_tipo_conta'] ?? false)
            <div>
                <label for="mot_tipo_conta" class="block font-medium text-sm text-gray-700">Tipo de Conta*</label>
                <select name="mot_tipo_conta" id="mot_tipo_conta" class="mt-1 block w-full">
                    <option value="Conta Salário" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta Salário' ? 'selected' : ''}}>Conta Salário</option>
                    <option value="Conta Corrente" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta Corrente' ? 'selected' : ''}}>Conta Corrente</option>
                    <option value="Conta de Pagamentos" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta de Pagamentos' ? 'selected' : ''}}>Conta de Pagamentos</option>
                    <option value="Conta PJ" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta PJ' ? 'selected' : ''}}>Conta PJ</option>
                    <option value="Conta Poupança" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta Poupança' ? 'selected' : ''}}>Conta Poupança</option>
                    <option value="Conta Digital/Eletrônica" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta Digital/Eletrônica' ? 'selected' : ''}}>Conta Digital/Eletrônica</option>
                    <option value="Conta Conjunta" {{ old('mot_tipo_conta', $motorista->mot_conta) == 'Conta Conjunta' ? 'selected' : ''}}>Conta Conjunta</option>
                </select>
            </div>
            @endif
            
            {{-- Chave de configuração: 'usar_chave_pix' --}}
            @if($configuracoes['usar_chave_pix'] ?? false)
            <div>
                <label for="mot_chave_pix" class="block font-medium text-sm text-gray-700">Chave PIX*</label>
                <input type="text" name="mot_chave_pix" id="mot_chave_pix" class="mt-1 block w-full" value="{{ old('mot_chave_pix', $motorista->mot_chave_pix) }}">
            </div>
            @endif
        </div>
    </div>
    @endif
    
    {{-- Chave de configuração: 'usar_observacoes' --}}
    @if($configuracoes['usar_observacoes'] ?? false)
    <div class="form-section">
        <h3 class="form-section-title">Observações</h3>
        <div>
            <label for="mot_observacoes" class="block font-medium text-sm text-gray-700">Observações Gerais</label>
            <textarea name="mot_observacoes" id="mot_observacoes" rows="3" class="mt-1 block w-full">{{ old('mot_observacoes', $motorista->mot_observacoes) }}</textarea>
        </div>
    </div>
    @endif
</div>

<div class="flex items-center justify-end mt-8">
    <a href="{{ route('motoristas.index') }}" class="btn-secondary mr-4">Cancelar</a>
    <button type="submit" class="btn-primary">Salvar Motorista</button>
</div>

@push('scripts')
<script>
    // É uma boa prática envolver todo o código em um listener que espera o DOM ser carregado.
    document.addEventListener('DOMContentLoaded', function () {

        // --- SEÇÃO DE MÁSCARAS ---
        // Verifica se o jQuery e o plugin de máscara estão disponíveis
        if (typeof $ === 'function' && typeof $.fn.mask === 'function') {
            $('#mot_cpf').mask('000.000.000-00', {reverse: true});
            $('#mot_cep').mask('00000-000');
            
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
        } else {
            console.warn('jQuery ou o plugin jQuery Mask não foram carregados. As máscaras de campo não funcionarão.');
        }

        // --- SEÇÃO DE CONSULTA DE CEP ---
        const cepInput = document.getElementById('mot_cep');

        // Adiciona o listener apenas se o campo CEP existir na página
        if (cepInput) {
            cepInput.addEventListener('blur', function () {
                // Remove caracteres não numéricos do CEP
                const cep = cepInput.value.replace(/\D/g, '');

                // Verifica se o CEP tem o tamanho correto (8 dígitos)
                if (cep.length === 8) {
                    // Faz a requisição para a API ViaCEP
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => {
                            // Verifica se a requisição foi bem sucedida
                            if (!response.ok) {
                                throw new Error('Erro na rede ou CEP inválido');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Se a API retornar um erro (CEP não encontrado), 'data.erro' será true
                            if (!data.erro) {
                                // Preenche os campos de endereço com os dados recebidos
                                document.getElementById('mot_endereco').value = data.logradouro || '';
                                document.getElementById('mot_bairro').value = data.bairro || '';
                                document.getElementById('mot_cidade').value = data.localidade || '';
                                document.getElementById('mot_estado').value = data.uf || '';
                                // Foca no campo de número para o usuário preencher
                                document.getElementById('mot_numero').focus(); 
                            } else {
                                alert('CEP não encontrado. Verifique o número digitado.');
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao buscar o CEP:', error);
                            alert('Não foi possível consultar o CEP. Verifique sua conexão ou tente novamente mais tarde.');
                        });
                }
            });
        }
    });
</script>
@endpush
