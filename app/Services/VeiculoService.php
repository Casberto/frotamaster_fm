<?php

namespace App\Services;

use App\Models\Veiculo;
use App\Http\Requests\StoreVeiculoRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\LogService;

class VeiculoService
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Cria ou atualiza um veículo.
     */
    public function salvarVeiculo(StoreVeiculoRequest $request, Veiculo $veiculo): Veiculo
    {
        $idEmpresa = Auth::user()->id_empresa;
        $dadosAntigos = $veiculo->exists ? $veiculo->getOriginal() : null;

        $validatedData = $request->validated();
        
        // Atribui dados que não vêm diretamente do formulário validado
        $validatedData['vei_emp_id'] = $idEmpresa;
        $validatedData['vei_user_id'] = Auth::id();
        $validatedData['vei_placa'] = strtoupper($validatedData['vei_placa']);
        if (isset($validatedData['vei_chassi'])) {
            $validatedData['vei_chassi'] = strtoupper($validatedData['vei_chassi']);
        }
        $validatedData['vei_venc_licenciamento'] = $this->calcularVencimentoLicenciamento($request->vei_placa);

        // Preenche o modelo e salva
        $veiculo->fill($validatedData);
        $veiculo->save();

        // Registra o log
        $acao = $dadosAntigos ? 'Atualização de Veículo' : 'Criação de Veículo';
        $this->logService->registrar($acao, 'Veículos', $veiculo, $dadosAntigos);

        return $veiculo;
    }

    /**
     * Remove um veículo do banco de dados.
     */
    public function deletarVeiculo(Veiculo $veiculo): void
    {
        // Mecanismo de autorização para garantir que o usuário pertence à empresa do veículo
        if ($veiculo->vei_emp_id !== Auth::user()->id_empresa) {
            abort(403, 'Acesso não autorizado.');
        }

        // Registra o log antes de deletar
        $this->logService->registrar('Exclusão de Veículo', 'Veículos', $veiculo, $veiculo->toArray());
        
        $veiculo->delete();
    }

    /**
     * Calcula a data de vencimento do licenciamento com base no final da placa.
     */
    private function calcularVencimentoLicenciamento(?string $placa): ?string
    {
        if (empty($placa)) {
            return null;
        }

        $ultimoDigito = substr(preg_replace('/[^0-9]/', '', $placa), -1);
        
        if ($ultimoDigito === '') {
            return null;
        }

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
            // Se a data de vencimento calculada já passou este ano, agenda para o próximo ano
            if ($vencimento->isPast()) {
                return $vencimento->addYear()->toDateString();
            }
            return $vencimento->toDateString();
        }

        return null;
    }
}
