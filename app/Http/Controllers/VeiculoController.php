<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\LogService;
use Carbon\Carbon;

class VeiculoController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Exibe uma lista dos veículos da empresa logada.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }

        $idEmpresa = Auth::user()->id_empresa;

        // Inicia a query base de veículos
        $query = Veiculo::where('vei_emp_id', $idEmpresa);

        // Aplica o filtro de busca por termo (placa ou modelo)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vei_placa', 'like', $searchTerm)
                  ->orWhere('vei_modelo', 'like', $searchTerm);
            });
        }

        // Aplica o filtro por tipo de veículo
        if ($request->filled('tipo')) {
            $query->where('vei_tipo', $request->tipo);
        }

        // Aplica o filtro por status
        if ($request->filled('status')) {
            $query->where('vei_status', $request->status);
        }

        // Ordena e pagina os resultados
        $veiculos = $query->latest('created_at')->paginate(10);

        // Array de tipos de veículo para o filtro
        $tipos = [
            '6' => 'Automóvel', '13' => 'Camioneta', '14' => 'Caminhão', '17' => 'Caminhão Trator',
            '2' => 'Ciclomotor', '7' => 'Micro-ônibus', '4' => 'Motocicleta', '3' => 'Motoneta',
            '8' => 'Ônibus', '21' => 'Quadriciclo', '10' => 'Reboque', '11' => 'Semirreboque',
            '5' => 'Triciclo', '25' => 'Utilitário', '22' => 'Chassi Plataforma',
        ];

        return view('veiculos.index', compact('veiculos', 'tipos'));
    }


    /**
     * Mostra o formulário para criar um novo veículo.
     */
    public function create()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Apenas usuários de empresas podem cadastrar veículos.');
        }
        return view('veiculos.create');
    }

    /**
     * Armazena um novo veículo no banco de dados.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->id_empresa) {
            return back()->with('error', 'Apenas usuários vinculados a uma empresa podem cadastrar veículos.')->withInput();
        }

        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $request->validate($this->getValidationRules($idEmpresa));

        $validatedData['vei_emp_id'] = $idEmpresa;
        $validatedData['vei_user_id'] = Auth::id();
        $validatedData['vei_placa'] = strtoupper($validatedData['vei_placa']);
        if (isset($validatedData['vei_chassi'])) {
            $validatedData['vei_chassi'] = strtoupper($validatedData['vei_chassi']);
        }
        $validatedData['vei_venc_licenciamento'] = $this->calcularVencimentoLicenciamento($request->vei_placa);

        $veiculo = Veiculo::create($validatedData);

        $this->logService->registrar('Criação de Veículo', 'Veículos', $veiculo);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um veículo existente.
     */
    public function edit(Veiculo $veiculo)
    {
        // Mecanismo de autorização manual
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('veiculos.edit', compact('veiculo'));
    }

    /**
     * Atualiza um veículo específico no banco de dados.
     */
    public function update(Request $request, Veiculo $veiculo)
    {
        // Mecanismo de autorização manual
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $idEmpresa = Auth::user()->id_empresa;
        $veiculoId = $veiculo->vei_id;

        $validatedData = $request->validate($this->getValidationRules($idEmpresa, $veiculoId));

        $validatedData['vei_placa'] = strtoupper($validatedData['vei_placa']);
        if (isset($validatedData['vei_chassi'])) {
            $validatedData['vei_chassi'] = strtoupper($validatedData['vei_chassi']);
        }
        $validatedData['vei_venc_licenciamento'] = $this->calcularVencimentoLicenciamento($request->vei_placa);

        $dadosAntigos = $veiculo->getOriginal();
        $veiculo->update($validatedData);

        $this->logService->registrar('Atualização de Veículo', 'Veículos', $veiculo, $dadosAntigos);

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Remove um veículo do banco de dados.
     */
    public function destroy(Veiculo $veiculo)
    {
        // Mecanismo de autorização manual
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        $this->logService->registrar('Exclusão de Veículo', 'Veículos', $veiculo, $veiculo->toArray());
        
        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veículo removido com sucesso!');
    }

    /**
     * Centraliza as regras de validação para store e update com os novos campos.
     */
    private function getValidationRules($idEmpresa, $veiculoId = null)
    {
        return [
            'vei_placa' => ['required', 'string', 'max:8', Rule::unique('veiculos', 'vei_placa')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            'vei_fabricante' => 'required|string|max:50',
            'vei_modelo' => 'required|string|max:50',
            'vei_cor_predominante' => 'required|string|max:30',
            'vei_ano_fab' => 'required|digits:4|integer|min:1940',
            'vei_ano_mod' => 'required|digits:4|integer|gte:vei_ano_fab',
            'vei_tipo' => 'required|integer',
            'vei_especie' => 'required|integer',
            'vei_carroceria' => 'required|integer',
            'vei_segmento' => 'required|integer|in:1,2,3,4',
            'vei_chassi' => ['nullable', 'string', 'size:17', Rule::unique('veiculos', 'vei_chassi')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            'vei_renavam' => ['nullable', 'string', 'min:9', 'max:11', Rule::unique('veiculos', 'vei_renavam')->where(fn ($query) => $query->where('vei_emp_id', $idEmpresa))->ignore($veiculoId, 'vei_id')],
            
            'vei_km_inicial' => 'required|integer|min:0|max:9999999',
            'vei_km_atual' => 'required|integer|gte:vei_km_inicial|max:9999999',
            'vei_combustivel' => 'required|integer|in:1,2,3,4,5,6',
            'vei_cap_tanque' => 'nullable|numeric|min:0',
            'vei_potencia' => 'nullable|string|max:10',
            'vei_cilindradas' => 'nullable|string|max:10',
            'vei_num_motor' => 'nullable|string|max:30',
            'vei_crv' => 'nullable|string|max:12',
            'vei_data_licenciamento' => 'nullable|date',
            'vei_antt' => 'nullable|string|max:20',
            'vei_tara' => 'nullable|integer|min:0',
            'vei_lotacao' => 'nullable|integer|min:0',
            'vei_pbt' => 'nullable|integer|min:0',
            'vei_data_aquisicao' => 'required|date',
            'vei_valor_aquisicao' => 'nullable|numeric|min:0',
            'vei_data_venda' => 'nullable|date|after_or_equal:vei_data_aquisicao',
            'vei_valor_venda' => 'nullable|numeric|min:0',
            'vei_status' => ['required', 'integer', Rule::in([1, 2, 3, 4])],
            'vei_obs' => 'nullable|string',
        ];
    }

    /**
     * Calcula a data de vencimento do licenciamento com base no final da placa.
     */
    private function calcularVencimentoLicenciamento($placa)
    {
        if (empty($placa)) {
            return null;
        }

        $ultimoDigito = substr(preg_replace('/[^0-9]/', '', $placa), -1);
        $anoAtual = date('Y');

        $mesVencimento = match ($ultimoDigito) {
            '1', '2' => 7,
            '3', '4' => 8,
            '5', '6' => 9,
            '7', '8' => 10,
            '9' => 11,
            '0' => 12,
            default => null,
        };

        if ($mesVencimento) {
            $vencimento = Carbon::create($anoAtual, $mesVencimento)->endOfMonth();
            if ($vencimento->isPast()) {
                return $vencimento->addYear()->toDateString();
            }
            return $vencimento->toDateString();
        }

        return null;
    }
}

