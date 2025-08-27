<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\LogService; // Mantido o seu LogService
use Carbon\Carbon;

class VeiculoController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function index()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('id_empresa', $idEmpresa)->latest()->paginate(10);
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        if (!Auth::user()->id_empresa) {
            return redirect()->route('dashboard')->with('error', 'Apenas usuários de empresas podem cadastrar veículos.');
        }
        return view('veiculos.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->id_empresa) {
            return back()->with('error', 'Apenas usuários vinculados a uma empresa podem cadastrar veículos.')->withInput();
        }

        $idEmpresa = Auth::user()->id_empresa;

        // Valida os dados usando o método centralizado
        $validatedData = $request->validate($this->getValidationRules($idEmpresa));

        // Formata e adiciona dados antes de salvar
        $validatedData['id_empresa'] = $idEmpresa;
        $validatedData['placa'] = strtoupper($validatedData['placa']);
        if (isset($validatedData['chassi'])) {
            $validatedData['chassi'] = strtoupper($validatedData['chassi']);
        }
        // Calcula e adiciona o vencimento do licenciamento
        $validatedData['vencimento_licenciamento'] = $this->calcularVencimentoLicenciamento($request->placa);

        $veiculo = Veiculo::create($validatedData);

        $this->logService->registrar('Criação de Veículo', 'Veículos', $veiculo);

        return redirect()->route('veiculos.index')->with('success', 'Veículo cadastrado com sucesso!');
    }

    public function edit(Veiculo $veiculo)
    {
        // Utilizando a policy/gate para autorização (mais seguro e padrão Laravel)
        $this->authorize('view', $veiculo);
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        $this->authorize('update', $veiculo);

        $idEmpresa = Auth::user()->id_empresa;

        // Valida os dados usando o método centralizado, ignorando o ID do veículo atual
        $validatedData = $request->validate($this->getValidationRules($idEmpresa, $veiculo->id));
        
        // Formata e adiciona dados antes de salvar
        $validatedData['placa'] = strtoupper($validatedData['placa']);
        if (isset($validatedData['chassi'])) {
            $validatedData['chassi'] = strtoupper($validatedData['chassi']);
        }
        // Calcula e adiciona o vencimento do licenciamento
        $validatedData['vencimento_licenciamento'] = $this->calcularVencimentoLicenciamento($request->placa);
        
        $dadosAntigos = $veiculo->getOriginal();

        $veiculo->update($validatedData);

        $this->logService->registrar('Atualização de Veículo', 'Veículos', $veiculo, $dadosAntigos);

        return redirect()->route('veiculos.index')->with('success', 'Veículo atualizado com sucesso!');
    }
    
    public function destroy(Veiculo $veiculo)
    {
        $this->authorize('delete', $veiculo);

        $dadosAntigos = $veiculo->toArray();
        $veiculo->delete();
        $this->logService->registrar('Exclusão de Veículo', 'Veículos', (new Veiculo())->forceFill($dadosAntigos));

        return redirect()->route('veiculos.index')->with('success', 'Veículo removido com sucesso!');
    }

    /**
     * Centraliza as regras de validação para store e update.
     */
    private function getValidationRules($idEmpresa, $veiculoId = null)
    {
        return [
            'placa' => ['required', 'string', 'max:8', Rule::unique('veiculos')->where(function ($query) use ($idEmpresa) {
                return $query->where('id_empresa', $idEmpresa);
            })->ignore($veiculoId)],
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'ano_fabricacao' => 'required|digits:4|integer|min:1940',
            'ano_modelo' => 'required|digits:4|integer|gte:ano_fabricacao', // Mantida sua regra
            'tipo_veiculo' => ['required', Rule::in(['carro', 'moto', 'caminhao', 'van', 'outro'])],
            'chassi' => ['nullable', 'string', 'size:17', Rule::unique('veiculos')->where(function ($query) use ($idEmpresa) {
                return $query->where('id_empresa', $idEmpresa);
            })->ignore($veiculoId)],
            'renavam' => ['nullable', 'string', 'min:9', 'max:11', Rule::unique('veiculos')->where(function ($query) use ($idEmpresa) {
                return $query->where('id_empresa', $idEmpresa);
            })->ignore($veiculoId)],
            'quilometragem_inicial' => 'required|integer|min:0|max:9999999',
            'quilometragem_atual' => 'required|integer|gte:quilometragem_inicial|max:9999999', // Mantida sua regra
            'tipo_combustivel' => ['required', Rule::in(['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico'])],
            'capacidade_tanque' => 'nullable|numeric|min:0',
            // Novos campos
            'seguradora' => 'nullable|string|max:255',
            'apolice_seguro' => 'nullable|string|max:255',
            'vencimento_apolice' => 'nullable|date',
            'km_troca_pneus' => 'nullable|integer|min:0',
            'data_troca_pneus' => 'nullable|date',
            // Campos existentes
            'data_aquisicao' => 'nullable|date',
            'status' => ['required', Rule::in(['ativo', 'inativo', 'em_manutencao', 'vendido'])],
            'observacoes' => 'nullable|string',
        ];
    }

    /**
     * Calcula a data de vencimento do licenciamento com base no final da placa.
     * Calendário de SP como referência.
     */
    private function calcularVencimentoLicenciamento($placa)
    {
        if (empty($placa)) {
            return null;
        }

        $ultimoDigito = substr($placa, -1);
        $mesVencimento = match ($ultimoDigito) {
            '1' => 7, // Julho
            '2' => 8, // Agosto
            '3' => 9, // Setembro
            '4' => 10, // Outubro
            '5', '6' => 11, // Novembro
            '7', '8', '9', '0' => 12, // Dezembro
            default => null,
        };

        if ($mesVencimento) {
            // Define o vencimento para o último dia do mês correspondente no ano atual.
            return Carbon::create(date('Y'), $mesVencimento)->endOfMonth()->toDateString();
        }

        return null;
    }
}
