<?php

namespace App\Http\Controllers;

// Models
use App\Models\Empresa;
use App\Models\User;
use App\Models\Licenca;
use App\Models\ConfiguracaoEmpresa;
use App\Models\ConfiguracaoPadrao;

// Illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Notifications\ResetPassword;

// Others
use App\Services\LogService;
use Carbon\Carbon;


class EmpresaController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        $empresas = Empresa::latest()->paginate(10);
        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            'email_contato' => 'required|email|max:255|unique:users,email',
            'telefone_contato' => 'required|string|max:20',
            'tipo' => 'required|in:PJ,PF',
        ]);

        // Usar uma transação para garantir a integridade dos dados
        DB::transaction(function () use ($request) {
            // 1. Cria a empresa
            $empresa = Empresa::create($request->all());

            // 2. Cria o usuário Master associado
            $user = User::create([
                'name' => 'Master ' . $request->nome_fantasia,
                'email' => $request->email_contato,
                'password' => Hash::make(Str::random(16)), // Senha temporária segura
                'role' => 'master',
                'email_verified_at' => now(),
                'id_empresa' => $empresa->id,
            ]);

            // 3. Registra o log da criação da empresa
            $this->logService->registrar('Criação de Empresa', 'Empresas', $empresa);

            // 4. Cria a licença para a nova empresa
            Licenca::create([
                'id_empresa' => $empresa->id,
                'plano' => 'Mensal',
                'id_usuario_criador' => auth()->id(),
                'valor_pago' => 0.00,
                'data_inicio' => Carbon::today(),
                'data_vencimento' => Carbon::today()->addDays(30),
                'status' => 'ativo',
            ]);

            // 5. Sincroniza as configurações padrão para a nova empresa
            $configuracoesPadrao = ConfiguracaoPadrao::all();
            
            // Campos permitidos para Pessoa Física (os demais serão desabilitados)
            $camposPermitidosPF = [
                'usar_usuario', 
                'usar_apelido', 
                'usar_data_nascimento', 
                'usar_genero', 
                'usar_nacionalidade', 
                'usar_estado_civil', 
                'usar_nome_mae', 
                'usar_nome_pai', 
                'usar_email', 
                'usar_telefone1', 
                'usar_cep', 
                'usar_endereco', 
                'usar_numero', 
                'usar_complemento', 
                'usar_bairro', 
                'usar_cidade', 
                'usar_estado', 
                'exige_cnh',
                'usar_cnh_numero', 
                'usar_cnh_categoria', 
                'usar_cnh_data_emissao', 
                'usar_cnh_data_validade', 
                'usar_cnh_primeira_habilitacao', 
                'usar_cnh_uf', 
                'usar_cnh_observacoes'
            ];

            foreach ($configuracoesPadrao as $configPadrao) {
                $valor = $configPadrao->cfp_valor;

                // Lógica para Pessoa Física
                if ($request->tipo === 'PF') {
                    // Verifica se é uma configuração de "usar_" ou "exige_"
                    if (str_starts_with($configPadrao->cfp_chave, 'usar_') || str_starts_with($configPadrao->cfp_chave, 'exige_')) {
                        if (in_array($configPadrao->cfp_chave, $camposPermitidosPF)) {
                            $valor = 1; // Habilita
                        } else {
                            $valor = 0; // Desabilita
                        }
                    }
                }

                ConfiguracaoEmpresa::create([
                    'cfe_emp_id' => $empresa->id,
                    'cfe_cfp_id' => $configPadrao->cfp_id,
                    'cfe_valor'  => $valor,
                ]);
            }

            // 6. Envia o e-mail para o usuário definir a senha de forma explícita
            $token = Password::broker()->createToken($user);
            $user->notify(new ResetPassword($token));
        });

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa criada com sucesso! Um e-mail de boas-vindas foi enviado para o usuário definir sua senha.');
    }

    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj,' . $empresa->id,
            'email_contato' => 'required|email|max:255',
            'telefone_contato' => 'required|string|max:20',
            'tipo' => 'required|in:PJ,PF',
        ]);

        $dadosAntigos = $empresa->getOriginal();
        
        $empresa->update($request->all());

        $this->logService->registrar('Atualização de Empresa', 'Empresas', $empresa, $dadosAntigos);

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Empresa $empresa)
    {
        $dadosAntigos = $empresa->getOriginal();

        $empresa->delete();

        $this->logService->registrar('Exclusão de Empresa', 'Empresas', (new Empresa())->forceFill($dadosAntigos));
        
        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa removida com sucesso!');
    }
}
