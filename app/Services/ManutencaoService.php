<?php

namespace App\Services;

use App\Models\Manutencao;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Classe de serviço para encapsular a lógica de negócio de Manutenções.
 */
class ManutencaoService
{
    /**
     * Salva (cria ou atualiza) um registro de manutenção e executa as regras de negócio associadas.
     *
     * @param Request $request
     * @param Manutencao $manutencao
     * @return Manutencao
     * @throws \Exception
     */
    public function salvarManutencao(Request $request, Manutencao $manutencao): Manutencao
    {
        $user = Auth::user();
        $idEmpresa = $user->id_empresa;
        $eraNova = !$manutencao->exists;
        $statusOriginal = $manutencao->getOriginal('man_status');

        $validatedData = $request->validated(); // Os dados já chegam validados pelo Form Request

        DB::beginTransaction();
        try {
            $validatedData['man_custo_pecas'] = $this->limparValor($request->man_custo_pecas);
            $validatedData['man_custo_mao_de_obra'] = $this->limparValor($request->man_custo_mao_de_obra);

            $manutencao->fill($validatedData);
            $manutencao->man_emp_id = $idEmpresa;
            $manutencao->man_user_id = $user->id;
            $manutencao->save();

            $custoTotalServicos = 0;
            if ($request->has('servicos')) {
                $dadosParaPivot = [];
                foreach ($request->servicos as $servico) {
                    $custoLimpo = $this->limparValor($servico['custo']);
                    $dadosParaPivot[$servico['id']] = [
                        'ms_custo' => $custoLimpo,
                        'ms_garantia' => !empty($servico['garantia']) ? $servico['garantia'] : null,
                    ];
                    $custoTotalServicos += $custoLimpo;
                }
                $manutencao->servicos()->sync($dadosParaPivot);
            } else {
                $manutencao->servicos()->sync([]);
            }

            $manutencao->man_custo_total = $custoTotalServicos + $manutencao->man_custo_pecas + $manutencao->man_custo_mao_de_obra;
            $manutencao->save();

            // Verifica se a manutenção foi concluída nesta operação
            $foiConcluidaAgora = $manutencao->man_status === 'concluida' && ($eraNova || $statusOriginal !== 'concluida');

            if ($foiConcluidaAgora) {
                $this->processarRegrasDeNegocioPosConclusao($manutencao);
            }

            DB::commit();

            return $manutencao;

        } catch (\Exception $e) {
            DB::rollBack();
            // Re-lança a exceção para que o controller possa tratá-la
            throw $e;
        }
    }

    /**
     * Limpa e converte um valor monetário de string para float.
     */
    private function limparValor($valor): float
    {
        if (empty($valor)) {
            return 0;
        }

        $valor = (string) $valor;
        $lastDot = strrpos($valor, '.');
        $lastComma = strrpos($valor, ',');

        if ($lastComma !== false && ($lastDot === false || $lastComma > $lastDot)) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } else if ($lastDot !== false && ($lastComma === false || $lastDot > $lastComma)) {
            $valor = str_replace(',', '', $valor);
        }

        return floatval($valor);
    }

    /**
     * Centraliza a execução das regras de negócio pós-conclusão.
     */
    private function processarRegrasDeNegocioPosConclusao(Manutencao $manutencao): void
    {
        $manutencao->load('veiculo', 'servicos');
        $this->atualizarKmVeiculo($manutencao);
        $this->agendarProximaManutencao($manutencao);
    }

    /**
     * REGRA 1: Se o KM da manutenção for maior, atualiza o KM do veículo.
     */
    private function atualizarKmVeiculo(Manutencao $manutencao): void
    {
        $veiculo = $manutencao->veiculo;
        if ($veiculo && $manutencao->man_km > $veiculo->vei_km_atual) {
            $veiculo->vei_km_atual = $manutencao->man_km;
            $veiculo->save();
            Log::info("KM do veículo {$veiculo->vei_placa} atualizado para {$manutencao->man_km} via manutenção #{$manutencao->man_id}.");
        }
    }

    /**
     * REGRA 2: Cria uma nova manutenção agendada se a atual for preventiva e concluída.
     */
    private function agendarProximaManutencao(Manutencao $manutencao): void
    {
        if ($manutencao->man_tipo !== 'preventiva' || (!$manutencao->man_prox_revisao_data && !$manutencao->man_prox_revisao_km)) {
            return;
        }



        $novaManutencao = new Manutencao([
            'man_vei_id' => $manutencao->man_vei_id,
            'man_emp_id' => $manutencao->man_emp_id,
            'man_user_id' => Auth::id() ?? $manutencao->man_user_id,
            'man_for_id' => $manutencao->man_for_id,
            'man_tipo' => 'preventiva',
            'man_status' => 'agendada',
            'man_data_inicio' => $manutencao->man_prox_revisao_data,
            'man_km' => $manutencao->man_prox_revisao_km,
            'man_custo_total' => 0,
        ]);
        $novaManutencao->save();

        $servicosIds = $manutencao->servicos()->pluck('ser_id');
        if ($servicosIds->isNotEmpty()) {
            $novaManutencao->servicos()->attach($servicosIds);
        }

        Log::info("Manutenção #{$novaManutencao->man_id} agendada automaticamente a partir da manutenção #{$manutencao->man_id}.");
    }
}
