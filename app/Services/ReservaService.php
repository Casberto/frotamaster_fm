<?php

namespace App\Services;

use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;

class ReservaService
{
    /**
     * Verifica conflitos de horário (Bloqueio ou Aviso).
     * Retorna MessageBag se houver erro, ou null se estiver OK.
     */
    public function verificarConflitos(array $dados, ?int $ignorarReservaId = null, bool $forceCreate = false): ?MessageBag
    {
        // Se o utilizador forçou a criação, ignoramos o aviso de pendente
        if ($forceCreate) {
            return null; 
        }

        $veiculoId = $dados['res_vei_id'] ?? null;

        // Se não foi selecionado um veículo (ex: "A definir"), não validamos conflitos.
        if (empty($veiculoId)) {
            return null;
        }

        $empresaId = Auth::user()->id_empresa;
        
        if (empty($dados['res_data_inicio']) || empty($dados['res_data_fim'])) {
            return null;
        }

        $inicio = Carbon::parse($dados['res_data_inicio']);
        $fim = Carbon::parse($dados['res_data_fim']);

        // Se a NOVA reserva for dia todo, expandimos os horários para cobrir o dia inteiro
        if (!empty($dados['res_dia_todo'])) {
            $inicio->startOfDay();
            $fim->endOfDay();
        }

        // 1. VERIFICAÇÃO DE BLOQUEIO (Hard Block - Aprovada ou Em Uso)
        $conflitoBloqueio = Reserva::where('res_emp_id', $empresaId)
            ->where('res_vei_id', $veiculoId)
            ->when($ignorarReservaId, fn($query) => $query->where('res_id', '!=', $ignorarReservaId))
            ->whereIn('res_status', ['aprovada', 'em_uso'])
            ->where(function ($query) use ($inicio, $fim) {
                $query->where(function ($q) use ($inicio, $fim) {
                    // CASO A: Reserva no banco NÃO é dia todo (compara horário exato)
                    // Lógica: (InicioA < FimB) E (FimA > InicioB)
                    $q->where('res_dia_todo', false)
                      ->where('res_data_inicio', '<', $fim)
                      ->where('res_data_fim', '>', $inicio);
                })->orWhere(function ($q) use ($inicio, $fim) {
                    // CASO B: Reserva no banco É dia todo (ignora hora, compara apenas datas)
                    // Usamos whereDate com <= e >= para garantir que qualquer hora do dia seja bloqueada
                    $q->where('res_dia_todo', true)
                      ->whereDate('res_data_inicio', '<=', $fim->toDateString()) // Data Inicio <= Data Fim Solicitada
                      ->whereDate('res_data_fim', '>=', $inicio->toDateString()); // Data Fim >= Data Inicio Solicitada
                });
            })
            ->exists();

        if ($conflitoBloqueio) {
            return new MessageBag(['res_vei_id' => 'Não é possível salvar. O veículo já possui uma reserva APROVADA ou EM USO que conflita com este período.']);
        }

        // 2. VERIFICAÇÃO DE AVISO (Soft Warning - Pendente)
        $conflitoAviso = Reserva::where('res_emp_id', $empresaId)
            ->where('res_vei_id', $veiculoId)
            ->when($ignorarReservaId, fn($query) => $query->where('res_id', '!=', $ignorarReservaId))
            ->where('res_status', 'pendente')
            ->where(function ($query) use ($inicio, $fim) {
                // Mesma lógica híbrida para pendentes
                $query->where(function ($q) use ($inicio, $fim) {
                    $q->where('res_dia_todo', false)
                      ->where('res_data_inicio', '<', $fim)
                      ->where('res_data_fim', '>', $inicio);
                })->orWhere(function ($q) use ($inicio, $fim) {
                    $q->where('res_dia_todo', true)
                      ->whereDate('res_data_inicio', '<=', $fim->toDateString())
                      ->whereDate('res_data_fim', '>=', $inicio->toDateString());
                });
            })
            ->exists();

        if ($conflitoAviso) {
            return new MessageBag(['warning_pendente' => 'Atenção: Já existe uma reserva PENDENTE para este veículo em um período conflitante.']);
        }
        
        return null;
    }
}