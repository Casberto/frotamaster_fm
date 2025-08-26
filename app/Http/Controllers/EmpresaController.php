<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\Licenca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            'email_contato' => 'required|email|max:255|unique:users,email',
            'telefone_contato' => 'required|string|max:20',
        ]);

        // Cria a empresa primeiro
        $empresa = Empresa::create($request->all());

        // LÓGICA PARA CRIAR O USUÁRIO MASTER DA EMPRESA
        $password = Str::random(8); // Gera uma senha aleatória de 8 caracteres

        $user = new User();

        $user->name = 'Master ' . $empresa->nome_fantasia;
        $user->email = $request->email_contato;
        $user->password = Hash::make($password);
        $user->role = 'master';
        $user->email_verified_at = now();
        $user->id_empresa = $empresa->id;
        
        $user->save();

        $this->logService->registrar('Criação de Empresa', 'Empresas', $empresa);

        Licenca::create([
            'id_empresa' => $empresa->id,
            'plano' => 'Mensal',
            'id_usuario_criador' => auth()->id(),
            'valor_pago' => 0.00,
            'data_inicio' => Carbon::today(),
            'data_vencimento' => Carbon::today()->addDays(30),
            'status' => 'ativo',
        ]);

        // Guarda as credenciais para exibir ao admin
        $credentials = [
            'email' => $user->email,
            'password' => $password,
        ];

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa criada com sucesso!')
                         ->with('credentials', $credentials); // Enviamos as credenciais para a view
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
