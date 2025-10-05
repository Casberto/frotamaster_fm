<?php

namespace App\Http\Controllers;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbastecimentoController extends Controller
{
    /**
     * Mostra uma lista de todos os abastecimentos da empresa.
     */
    public function index(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        // Inicia a query
        $query = Abastecimento::where('aba_emp_id', $idEmpresa)->with('veiculo');

        // Aplica os filtros
        if ($request->filled('veiculo_id')) {
            $query->where('aba_vei_id', $request->veiculo_id);
        }

        if ($request->filled('data_inicio')) {
            $query->where('aba_data', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('aba_data', '<=', $request->data_fim);
        }
        
        // Ordena e pagina os resultados
        $abastecimentos = $query->latest('aba_data')->paginate(15)->appends($request->query());

        // Carrega os veículos para o dropdown do filtro
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();

        return view('abastecimentos.index', compact('abastecimentos', 'veiculos'));
    }


    /**
     * Mostra o formulário para criar um novo abastecimento.
     */
    public function create()
    {
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();
        $fornecedores = Fornecedor::where('for_emp_id', $idEmpresa)->orderBy('for_nome_fantasia')->get();

        // Mapeia os dados necessários para o JavaScript
        $veiculosData = $veiculos->mapWithKeys(function ($veiculo) {
            return [$veiculo->vei_id => [
                'km' => $veiculo->vei_km_atual,
                'combustivel_tipo' => $veiculo->vei_combustivel,
            ]];
        });

        return view('abastecimentos.create', compact('veiculos', 'fornecedores', 'veiculosData'));
    }

    /**
     * Armazena um novo abastecimento no banco de dados.
     */
    public function store(Request $request)
    {
        $idEmpresa = Auth::user()->id_empresa;

        $validatedData = $this->validateRequest($request, $idEmpresa);
        
        $abastecimento = new Abastecimento($validatedData);
        $abastecimento->aba_emp_id = $idEmpresa;
        $abastecimento->aba_user_id = Auth::id();
        
        $this->processarValores($abastecimento, $validatedData);
        
        $abastecimento->save();
        
        $this->atualizarKmVeiculo($abastecimento->aba_vei_id, $abastecimento->aba_km);

        return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento registrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um abastecimento.
     */
    public function edit(Abastecimento $abastecimento)
    {
        $idEmpresa = Auth::user()->id_empresa;
        $veiculos = Veiculo::where('vei_emp_id', $idEmpresa)->orderBy('vei_placa')->get();
        $fornecedores = Fornecedor::where('for_emp_id', $idEmpresa)->orderBy('for_nome_fantasia')->get();
        
        $veiculosData = $veiculos->mapWithKeys(function ($veiculo) {
            return [$veiculo->vei_id => [
                'km' => $veiculo->vei_km_atual,
                'combustivel_tipo' => $veiculo->vei_combustivel,
            ]];
        });

        return view('abastecimentos.edit', compact('abastecimento', 'veiculos', 'fornecedores', 'veiculosData'));
    }

    /**
     * Atualiza um abastecimento no banco de dados.
     */
    public function update(Request $request, Abastecimento $abastecimento)
    {
        $idEmpresa = Auth::user()->id_empresa;
        
        $validatedData = $this->validateRequest($request, $idEmpresa, $abastecimento->aba_id);

        $abastecimento->fill($validatedData);
        $this->processarValores($abastecimento, $validatedData);
        $abastecimento->save();
        
        // Pode ser necessário re-calcular a KM do veículo
        // (lógica mais complexa, por enquanto atualizamos se for maior)
        $this->atualizarKmVeiculo($abastecimento->aba_vei_id, $abastecimento->aba_km);

        return redirect()->route('abastecimentos.index')->with('success', 'Abastecimento atualizado com sucesso!');
    }

    /**
     * Remove um abastecimento do banco de dados.
     */
    public function destroy(Abastecimento $abastecimento)
    {
        $abastecimento->delete();
        return redirect()->route('abastecimentos.index')->with('success', 'Registro de abastecimento removido com sucesso!');
    }

    /**
     * Valida os dados da request.
     */
    private function validateRequest(Request $request, int $idEmpresa, int $abastecimentoId = null): array
    {
        return $request->validate([
            'aba_vei_id' => ['required', Rule::exists('veiculos', 'vei_id')->where('vei_emp_id', $idEmpresa)],
            'aba_for_id' => ['nullable', Rule::exists('fornecedores', 'for_id')->where('for_emp_id', $idEmpresa)],
            'aba_data' => ['required', 'date', 'before_or_equal:today'],
            'aba_km' => ['required', 'integer', 'min:0'],
            'aba_combustivel' => ['nullable', 'integer'], // ID do combustível
            'aba_vlr_tot' => ['required', 'string'],
            'aba_qtd' => ['required', 'string'],
            'aba_vlr_und' => ['required', 'string'],
            'aba_tanque_inicio' => ['nullable', 'string'],
            'aba_tanque_cheio' => ['nullable', 'boolean'],
            'aba_pneus_calibrados' => ['nullable', 'boolean'],
            'aba_agua_verificada' => ['nullable', 'boolean'],
            'aba_oleo_verificado' => ['nullable', 'boolean'],
            'aba_obs' => ['nullable', 'string'],
        ]);
    }

    /**
     * Converte e atribui os valores numéricos.
     */
    private function processarValores(Abastecimento $abastecimento, array $validatedData): void
    {
        $limparValor = fn($v) => $v ? (float)str_replace(['.', ','], ['', '.'], $v) : 0;

        $abastecimento->aba_vlr_tot = $limparValor($validatedData['aba_vlr_tot']);
        $abastecimento->aba_qtd = $limparValor($validatedData['aba_qtd']);
        $abastecimento->aba_vlr_und = $limparValor($validatedData['aba_vlr_und']);

        $veiculo = Veiculo::find($abastecimento->aba_vei_id);
        
        // Adiciona uma verificação para garantir que o veículo foi encontrado
        if ($veiculo) {
            $abastecimento->aba_und_med = match ((int)$veiculo->vei_combustivel) {
                5 => 'kWh',
                4 => 'm³',
                default => 'L',
            };
        } else {
            $abastecimento->aba_und_med = 'L'; // Fallback
        }

        // Garante que os checkboxes não enviados sejam `false`
        $abastecimento->aba_tanque_cheio = $validatedData['aba_tanque_cheio'] ?? false;
        $abastecimento->aba_pneus_calibrados = $validatedData['aba_pneus_calibrados'] ?? false;
        $abastecimento->aba_agua_verificada = $validatedData['aba_agua_verificada'] ?? false;
        $abastecimento->aba_oleo_verificado = $validatedData['aba_oleo_verificado'] ?? false;
    }

    /**
     * Atualiza a quilometragem do veículo se o novo registro for maior.
     */
    private function atualizarKmVeiculo(int $veiculoId, int $novaKm): void
    {
        $veiculo = Veiculo::find($veiculoId);
        if ($veiculo && $novaKm > $veiculo->vei_km_atual) {
            $veiculo->vei_km_atual = $novaKm;
            $veiculo->save();
        }
    }
}

