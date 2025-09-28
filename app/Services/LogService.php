<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class LogService
{
    public function registrar(string $acao, string $tela, Model $modelo, array $dadosAntigos = null)
    {
        $user = Auth::user();

        Log::create([
            'id_empresa'      => $user->id_empresa,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'tela'            => $tela,
            'acao'            => $acao,
            // --- CORREÇÃO 1: Obter a chave primária da forma correta ---
            'registro_id'     => $modelo->getKey(),
            'registro_string' => $this->getRegistroString($modelo, $tela),
            'dados_antigos'   => $dadosAntigos ? json_encode($dadosAntigos) : null,
            'dados_novos'     => json_encode($modelo->toArray()),
        ]);
    }

    private function getRegistroString(Model $modelo, string $tela): string
    {
        // --- CORREÇÃO 2: Lógica adaptada para o novo modelo Veiculo ---
        if ($modelo instanceof \App\Models\Veiculo) {
            return "{$modelo->vei_placa} ({$modelo->vei_fabricante}/{$modelo->vei_modelo})";
        }
        
        // Mantém a lógica original para os outros modelos
        switch ($tela) {
            case 'Manutenções':
                return $modelo->descricao_servico . ' (Veículo: ' . optional($modelo->veiculo)->placa . ')';
            case 'Abastecimentos':
                return 'Abastecimento em ' . $modelo->data_abastecimento->format('d/m/Y') . ' (Veículo: ' . optional($modelo->veiculo)->placa . ')';
            case 'Empresas':
                return $modelo->nome_fantasia . ' (CNPJ: ' . $modelo->cnpj . ')';
            default:
                // Fallback seguro usando a chave primária
                return get_class($modelo) . ' ID: ' . $modelo->getKey();
        }
    }
}

