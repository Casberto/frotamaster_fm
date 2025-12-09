<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoEmpresa;
use App\Models\Motorista;
use App\Models\User; // Importa o Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\MotoristaStatusEnum;

class MotoristaController extends Controller
{
    /**
     * Busca as configurações do módulo de motoristas para a empresa logada.
     *
     * @return array
     */
    private function getMotoristaConfig(): array
    {
        $idEmpresa = Auth::user()->id_empresa;
        $configuracoes = ConfiguracaoEmpresa::where('cfe_emp_id', $idEmpresa)
            ->whereHas('configuracaoPadrao', function ($query) {
                $query->where('cfp_modulo', 'motoristas');
            })
            ->with('configuracaoPadrao')
            ->get();

        $configs = [];
        foreach ($configuracoes as $config) {
            if ($config->configuracaoPadrao) {
                $chave = $config->configuracaoPadrao->cfp_chave;
                $valor = $config->configuracaoPadrao->cfp_tipo === 'boolean'
                    ? filter_var($config->cfe_valor, FILTER_VALIDATE_BOOLEAN)
                    : $config->cfe_valor;
                $configs[$chave] = $valor;
            }
        }

        return $configs;
    }

    public function index(Request $request)
    {
        if (!Auth::user()->temPermissao('MOT001')) {
            return redirect()->route('dashboard')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $idEmpresa = Auth::user()->id_empresa;
        $query = Motorista::where('mot_emp_id', $idEmpresa);

        if ($request->filled('search')) {
            $query->where('mot_nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('mot_status', $request->status);
        }

        $motoristas = $query->latest()->paginate(15);

        return view('motoristas.index', compact('motoristas'));
    }

    public function create()
    {
        if (!Auth::user()->temPermissao('MOT002')) {
            return redirect()->route('motoristas.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $configuracoes = $this->getMotoristaConfig();
        $idEmpresa = Auth::user()->id_empresa;

        // Busca usuários ativos que não estão vinculados a nenhum motorista
        $users = User::where('id_empresa', $idEmpresa)
            ->where('status', 1)
            ->whereDoesntHave('motorista')
            ->get();

        return view('motoristas.create', [
            'motorista' => new Motorista(),
            'configuracoes' => $configuracoes,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->temPermissao('MOT002')) {
            return redirect()->route('motoristas.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        $idEmpresa = Auth::user()->id_empresa;
        $configuracoes = $this->getMotoristaConfig();
        $rules = $this->buildValidationRules($configuracoes, $idEmpresa);
        $messages = $this->getValidationMessages();
        $attributes = $this->getValidationAttributes();

        $request->validate($rules, $messages, $attributes);

        $dados = $request->all();
        $dados['mot_emp_id'] = $idEmpresa;

        Motorista::create($dados);

        return redirect()->route('motoristas.index')->with('success', 'Motorista cadastrado com sucesso!');
    }

    public function edit(Motorista $motorista)
    {
        if (!Auth::user()->temPermissao('MOT003')) {
            return redirect()->route('motoristas.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }
        $configuracoes = $this->getMotoristaConfig();
        $idEmpresa = Auth::user()->id_empresa;

        // Busca usuários que não estão vinculados a OUTRO motorista, ou o usuário já vinculado a este motorista
        $users = User::where('id_empresa', $idEmpresa)
            ->where('status', 1)
            ->where(function ($query) use ($motorista) {
                $query->whereDoesntHave('motorista')
                      ->orWhere('id', $motorista->mot_user_id);
            })
            ->get();

        return view('motoristas.edit', compact('motorista', 'configuracoes', 'users'));
    }

    public function update(Request $request, Motorista $motorista)
    {
        if (!Auth::user()->temPermissao('MOT003')) {
            return redirect()->route('motoristas.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $idEmpresa = Auth::user()->id_empresa;
        $configuracoes = $this->getMotoristaConfig();
        $rules = $this->buildValidationRules($configuracoes, $idEmpresa, $motorista->mot_id);
        $messages = $this->getValidationMessages();
        $attributes = $this->getValidationAttributes();

        $request->validate($rules, $messages, $attributes);

        $motorista->update($request->all());

        return redirect()->route('motoristas.index')->with('success', 'Motorista atualizado com sucesso!');
    }

    public function destroy(Motorista $motorista)
    {
        if (!Auth::user()->temPermissao('MOT004')) {
            return redirect()->route('motoristas.index')->with('error', 'O usuário não possuí permissão à essa tela');
        }
        if ($motorista->mot_emp_id !== Auth::user()->id_empresa) {
            abort(403);
        }

        $motorista->delete();

        return redirect()->route('motoristas.index')->with('success', 'Motorista removido com sucesso!');
    }

    /**
     * Constrói as regras de validação dinamicamente com base nas configurações.
     *
     * @param array $configuracoes
     * @param int $idEmpresa
     * @param int|null $motoristaId
     * @return array
     */
    private function buildValidationRules(array $configuracoes, int $idEmpresa, int $motoristaId = null): array
    {
        $allowedStatuses = [
            'Ativo',
            'Inativo',
            'Bloqueado',
            'Em treinamento',
            'Afastado',
            'Aguardando documentação',
            'Suspenso',
            'Rejeitado',
            'Em análise',
        ];
        
        $rules = [
            'mot_nome' => 'required|string|max:150',
            'mot_status' => ['required', 'string', Rule::in($allowedStatuses)],
        ];

        $fieldMap = [
            'usar_usuario' => ['mot_user_id', 'nullable|integer|exists:users,id'],
            'usar_apelido' => ['mot_apelido', 'nullable|string|max:80'],
            // Regra 1: Data de nascimento (mínimo 18 anos)
            'usar_data_nascimento' => ['mot_data_nascimento', 'nullable|date|before_or_equal:-18 years'],
            'usar_genero' => ['mot_genero', 'nullable|string|max:20'],
            'usar_nacionalidade' => ['mot_nacionalidade', 'nullable|string|max:50'],
            'usar_estado_civil' => ['mot_estado_civil', 'nullable|string|max:30'],
            'usar_nome_mae' => ['mot_nome_mae', 'nullable|string|max:150'],
            'usar_nome_pai' => ['mot_nome_pai', 'nullable|string|max:150'],
            'usar_cpf' => ['mot_cpf', 'nullable|string|max:14'],
            'usar_rg' => ['mot_rg', 'nullable|string|max:20'],
            'usar_orgao_emissor_rg' => ['mot_orgao_emissor_rg', 'nullable|string|max:20'],
            // Regra 2: Data de emissão do RG (não pode ser no futuro)
            'usar_data_emissao_rg' => ['mot_data_emissao_rg', 'nullable|date|before_or_equal:today'],
            'usar_pis' => ['mot_pis', 'nullable|string|max:20'],
            'usar_ctps_numero' => ['mot_ctps_numero', 'nullable|string|max:20'],
            'usar_ctps_serie' => ['mot_ctps_serie', 'nullable|string|max:20'],
            'usar_titulo_eleitor' => ['mot_titulo_eleitor', 'nullable|string|max:20'],
            'usar_zona_eleitoral' => ['mot_zona_eleitoral', 'nullable|string|max:10'],
            'usar_secao_eleitoral' => ['mot_secao_eleitoral', 'nullable|string|max:10'],
            'usar_cnh_numero' => ['mot_cnh_numero', 'nullable|string|max:20'],
            'usar_cnh_categoria' => ['mot_cnh_categoria', 'nullable|string|max:10'],
            'usar_cnh_data_emissao' => ['mot_cnh_data_emissao', 'nullable|date|before_or_equal:today'],
            'usar_cnh_data_validade' => ['mot_cnh_data_validade', 'nullable|date|after_or_equal:today'],
            'usar_cnh_primeira_habilitacao' => ['mot_cnh_primeira_habilitacao', 'nullable|date'],
            'usar_cnh_uf' => ['mot_cnh_uf', 'nullable|string|max:2'],
            'usar_email' => ['mot_email', 'nullable|email|max:120'],
            'usar_telefone1' => ['mot_telefone1', 'nullable|string|max:20'],
            'usar_telefone2' => ['mot_telefone2', 'nullable|string|max:20'],
            'usar_cep' => ['mot_cep', 'nullable|string|max:10'],
            'usar_endereco' => ['mot_endereco', 'nullable|string|max:150'],
            'usar_numero' => ['mot_numero', 'nullable|string|max:10'],
            'usar_complemento' => ['mot_complemento', 'nullable|string|max:50'],
            'usar_bairro' => ['mot_bairro', 'nullable|string|max:80'],
            'usar_cidade' => ['mot_cidade', 'nullable|string|max:100'],
            'usar_estado' => ['mot_estado', 'nullable|string|max:2'],
            'usar_data_admissao' => ['mot_data_admissao', 'nullable|date'],
            'usar_tipo_contrato' => ['mot_tipo_contrato', 'nullable|string|max:50'],
            'usar_categoria_profissional' => ['mot_categoria_profissional', 'nullable|string|max:50'],
            'usar_matricula_interna' => ['mot_matricula_interna', 'nullable|string|max:50'],
            'usar_observacoes' => ['mot_observacoes', 'nullable|string'],
            'usar_banco' => ['mot_banco', 'nullable|string|max:100'],
            'usar_agencia' => ['mot_agencia', 'nullable|string|max:10'],
            'usar_conta' => ['mot_conta', 'nullable|string|max:20'],
            'usar_tipo_conta' => ['mot_tipo_conta', 'nullable|string|max:20'],
            'usar_chave_pix' => ['mot_chave_pix', 'nullable|string|max:100'],
        ];

        foreach ($configuracoes as $chave => $ativo) {
            if ($ativo && isset($fieldMap[$chave])) {
                $fieldName = $fieldMap[$chave][0];
                $ruleString = $fieldMap[$chave][1];

                // Converte a string de regras base em um array
                $finalRules = explode('|', str_replace('nullable', 'required', $ruleString));
                
                // Adiciona regras dependentes condicionalmente
                
                // Regra 2 (Dependência): Data Emissão RG deve ser após Data Nascimento
                if ($chave === 'usar_data_emissao_rg' && ($configuracoes['usar_data_nascimento'] ?? false)) {
                    $finalRules[] = 'after_or_equal:mot_data_nascimento';
                }

                // Regra 3 (Dependência): Data Emissão CNH deve ser após Data Nascimento
                if ($chave === 'usar_cnh_data_emissao' && ($configuracoes['usar_data_nascimento'] ?? false)) {
                    $finalRules[] = 'after_or_equal:mot_data_nascimento';
                }

                // Regra 4 (Dependência): Data Validade CNH deve ser após Data Emissão CNH
                if ($chave === 'usar_cnh_data_validade' && ($configuracoes['usar_cnh_data_emissao'] ?? false)) {
                    $finalRules[] = 'after:mot_cnh_data_emissao';
                }

                // Atribui o array final de regras
                $rules[$fieldName] = $finalRules;


                // Lógica especial para CPF (mantida)
                if ($chave === 'usar_cpf') {
                    $uniqueRule = Rule::unique('motoristas')->where(fn ($query) => $query->where('mot_emp_id', $idEmpresa));
                    if ($motoristaId) {
                        $uniqueRule->ignore($motoristaId, 'mot_id');
                    }
                    $rules[$fieldName][] = $uniqueRule;
                }

                // Lógica especial para Usuário (mantida)
                if ($chave === 'usar_usuario') {
                    $rules['mot_user_id'] = [
                        'required',
                        'integer',
                        Rule::exists('users', 'id')->where(function ($query) use ($idEmpresa) {
                            $query->where('id_empresa', $idEmpresa);
                        })
                    ];
                }
            }
        }

        return $rules;
    }

    /**
     * Retorna as mensagens de validação personalizadas.
     *
     * @return array
     */
    private function getValidationMessages(): array
    {
        return [
            // Mensagens específicas para regras
            'mot_data_nascimento.before_or_equal' => 'O motorista deve ter pelo menos 18 anos.',
            'mot_data_emissao_rg.after_or_equal' => 'A data de emissão do RG deve ser igual ou posterior à data de nascimento.',
            'mot_cnh_data_emissao.after_or_equal' => 'A data de emissão da CNH deve ser igual ou posterior à data de nascimento.',
            'mot_cnh_data_validade.after' => 'A data de validade da CNH deve ser posterior à data de emissão.',
            'mot_cnh_data_validade.after_or_equal' => 'A data de validade da CNH não pode ser uma data passada.',
        ];
    }

    /**
     * Retorna os nomes amigáveis para os atributos de validação.
     *
     * @return array
     */
    private function getValidationAttributes(): array
    {
        return [
            'mot_user_id' => 'Usuário Vinculado',
            'mot_apelido' => 'Apelido',
            'mot_data_nascimento' => 'Data de Nascimento',
            'mot_genero' => 'Gênero',
            'mot_nacionalidade' => 'Nacionalidade',
            'mot_estado_civil' => 'Estado Civil',
            'mot_nome_mae' => 'Nome da Mãe',
            'mot_nome_pai' => 'Nome do Pai',
            'mot_cpf' => 'CPF',
            'mot_rg' => 'RG',
            'mot_orgao_emissor_rg' => 'Órgão Emissor do RG',
            'mot_data_emissao_rg' => 'Data de Emissão do RG',
            'mot_pis' => 'PIS',
            'mot_ctps_numero' => 'Número da CTPS',
            'mot_ctps_serie' => 'Série da CTPS',
            'mot_titulo_eleitor' => 'Título de Eleitor',
            'mot_zona_eleitoral' => 'Zona Eleitoral',
            'mot_secao_eleitoral' => 'Seção Eleitoral',
            'mot_cnh_numero' => 'Número da CNH',
            'mot_cnh_categoria' => 'Categoria da CNH',
            'mot_cnh_data_emissao' => 'Data de Emissão da CNH',
            'mot_cnh_data_validade' => 'Data de Validade da CNH',
            'mot_cnh_primeira_habilitacao' => 'Data da Primeira Habilitação',
            'mot_cnh_uf' => 'UF da CNH',
            'mot_email' => 'Email',
            'mot_telefone1' => 'Telefone 1',
            'mot_telefone2' => 'Telefone 2',
            'mot_cep' => 'CEP',
            'mot_endereco' => 'Endereço',
            'mot_numero' => 'Número',
            'mot_complemento' => 'Complemento',
            'mot_bairro' => 'Bairro',
            'mot_cidade' => 'Cidade',
            'mot_estado' => 'Estado',
            'mot_data_admissao' => 'Data de Admissão',
            'mot_data_demissao' => 'Data de Demissão',
            'mot_tipo_contrato' => 'Tipo de Contrato',
            'mot_categoria_profissional' => 'Categoria Profissional',
            'mot_matricula_interna' => 'Matrícula Interna',
            'mot_observacoes' => 'Observações',
            'mot_banco' => 'Banco',
            'mot_agencia' => 'Agência',
            'mot_conta' => 'Conta',
            'mot_tipo_conta' => 'Tipo de Conta',
            'mot_chave_pix' => 'Chave PIX',
        ];
    }
}

