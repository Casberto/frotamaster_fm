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
            'id_empresa' => $user->id_empresa,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'tela' => $tela,
            'acao' => $acao,
            'registro_id' => $modelo->id,
            'registro_string' => $this->getRegistroString($modelo, $tela),
            'dados_antigos' => $acao !== 'create' ? json_encode($dadosAntigos ?: $modelo->getOriginal()) : null,
            'dados_novos' => $acao !== 'delete' ? json_encode($modelo->getAttributes()) : null,
        ]);
    }

    private function getRegistroString(Model $modelo, string $tela): string
    {
        // O 'case' agora agrupa as telas para reutilizar a lógica
        switch ($tela) {
            case 'Veículos':
            case 'Veículos (via Abastecimento)':
            case 'Veículos (via Manutenção)':
                return $modelo->placa . ' (' . $modelo->marca . '/' . $modelo->modelo . ')';
            case 'Manutenções':
                return $modelo->descricao_servico . ' (Veículo: ' . $modelo->veiculo->placa . ')';
            case 'Abastecimentos':
                return 'Abastecimento em ' . $modelo->data_abastecimento->format('d/m/Y') . ' (Veículo: ' . $modelo->veiculo->placa . ')';
            case 'Empresas':
                return $modelo->nome_fantasia . ' (CNPJ: ' . $modelo->cnpj . ')';
            default:
                return 'ID: ' . $modelo->id;
        }
    }
}
