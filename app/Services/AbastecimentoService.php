<?php

namespace App\Services;

use App\Models\Abastecimento;
use App\Models\Veiculo;
use App\Http\Requests\StoreAbastecimentoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbastecimentoService
{
    /**
     * Salva um registro de abastecimento e executa as regras de negócio associadas.
     */
    public function salvarAbastecimento(StoreAbastecimentoRequest $request, Abastecimento $abastecimento): Abastecimento
    {
        $validatedData = $request->validated();
        
        $abastecimento->fill($validatedData);
        $abastecimento->aba_emp_id = Auth::user()->id_empresa;
        $abastecimento->aba_user_id = Auth::id();

        $this->processarValores($abastecimento, $validatedData);
        
        $abastecimento->save();
        
        $this->atualizarKmVeiculo($abastecimento->aba_vei_id, $abastecimento->aba_km);

        return $abastecimento;
    }

    /**
     * Converte e atribui os valores numéricos.
     */
    private function processarValores(Abastecimento $abastecimento, array $validatedData): void
    {
        $limparValor = fn($v) => $v ? (float)str_replace(['.', ','], ['', '.'], (string) $v) : 0;

        $abastecimento->aba_vlr_tot = $limparValor($validatedData['aba_vlr_tot']);
        $abastecimento->aba_qtd = $limparValor($validatedData['aba_qtd']);
        $abastecimento->aba_vlr_und = $limparValor($validatedData['aba_vlr_und']);

        $veiculo = Veiculo::find($abastecimento->aba_vei_id);
        
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
            Log::info("KM do veículo {$veiculo->vei_placa} atualizado para {$novaKm} via abastecimento #.");
        }
    }
}
