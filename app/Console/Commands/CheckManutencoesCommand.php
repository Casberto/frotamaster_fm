<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Manutencao;
use App\Models\User;
use App\Notifications\ManutencaoPendenteNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckManutencoesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-manutencoes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica manutenções próximas ou atrasadas e notifica os usuários responsáveis.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de manutenções...');

        // 1. Definição das Datas de Corte (zerando hora para comparar apenas datas)
        $hoje = Carbon::today();
        $daquiUmMes = $hoje->copy()->addMonth();
        $daquiUmaSemana = $hoje->copy()->addWeek();

        // Buscamos todas as manutenções pendentes/agendadas
        // Poderiamos filtrar por empresa, mas como é um comando sistêmico, vamos processar tudo
        // O ideal é filtrar status != 'Concluída' e != 'Cancelada'
        
        $manutencoes = Manutencao::with(['user', 'veiculo']) // Eager load para performance
            ->whereIn('man_status', ['Agendada', 'Pendente'])
            ->whereNotNull('man_prox_revisao_data')
            ->get();

        $count = 0;

        foreach ($manutencoes as $manutencao) {
            try {
                $dataRevisao = Carbon::parse($manutencao->man_prox_revisao_data)->startOfDay();
                $tipoAviso = null;

                // Lógica de Comparação
                if ($dataRevisao->equalTo($daquiUmMes)) {
                    $tipoAviso = '1_mes';
                } elseif ($dataRevisao->equalTo($daquiUmaSemana)) {
                    $tipoAviso = '1_semana';
                } elseif ($dataRevisao->equalTo($hoje)) {
                    $tipoAviso = 'hoje';
                } elseif ($dataRevisao->lessThan($hoje)) {
                    // Para atrasadas, para não spammar todo dia:
                    // Podemos verificar se já foi notificado hoje ou enviar a cada X dias.
                    // PROPOSTA SIMPLES: Enviar SE o dia do atraso for par, ou algo assim, ou apenas enviar sempre e o usuário limpa.
                    // O usuario pediu "notificar sobre atraso". Vamos emitir sempre por enquanto, o ideal seria guardar flag.
                    // Para minimizar spam, vamos notificar apenas se o atraso for de 1, 3, 7, 15, 30 dias...
                    // Mas para garantir que ele veja, vamos mandar sempre por enquanto.
                    $tipoAviso = 'atrasada';
                }

                if ($tipoAviso) {
                    // Quem notificar? O usuário dono do veículo ou o responsável da manutenção?
                    // Geralmente o gestor da frota (user da empresa) ou o proprio dono.
                    // Vamos notificar o user_id atrelado à manutenção (que criou ou é responsável).
                    
                    $user = $manutencao->user;
                    if ($user) {
                        $user->notify(new ManutencaoPendenteNotification($manutencao, $tipoAviso));
                        $this->info("Notificação enviada: [{$tipoAviso}] Manutenção ID {$manutencao->man_id} para User {$user->name}");
                        $count++;
                    }
                }

            } catch (\Exception $e) {
                $this->error("Erro ao processar manutenção ID {$manutencao->man_id}: " . $e->getMessage());
                Log::error("CheckManutencoesCommand Error: " . $e->getMessage());
            }
        }

        $this->info("Verificação concluída. {$count} notificações enviadas.");
    }
}
