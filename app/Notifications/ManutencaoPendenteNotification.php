<?php

namespace App\Notifications;

use App\Models\Manutencao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;
use App\Channels\CustomDatabaseChannel;

class ManutencaoPendenteNotification extends Notification
{
    use Queueable;

    protected $manutencao;

    /**
     * Create a new notification instance.
     */
    public function __construct(Manutencao $manutencao, protected string $tipoAviso = 'pendente')
    {
        $this->manutencao = $manutencao;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [CustomDatabaseChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $placa = $this->manutencao->veiculo ? $this->manutencao->veiculo->vei_placa : 'Veículo Indefinido';
        $tipoManutencao = $this->manutencao->man_tipo; // Preventiva, Corretiva
        
        // Definição de Textos baseados no Tipo de Aviso
        match ($this->tipoAviso) {
            '1_mes' => [
                'titulo' => 'Planejamento de Manutenção',
                'mensagem' => "A manutenção {$tipoManutencao} do veículo {$placa} vence em 1 mês.",
                'tipo' => 'info',
                'icon' => 'calendar',
            ],
            '1_semana' => [
                'titulo' => 'Manutenção Próxima',
                'mensagem' => "Atenção: A manutenção {$tipoManutencao} do veículo {$placa} vence na próxima semana.",
                'tipo' => 'warning',
                'icon' => 'clock',
            ],
            'hoje' => [
                'titulo' => 'Manutenção Vence Hoje',
                'mensagem' => "Urgente: A manutenção {$tipoManutencao} do veículo {$placa} deve ser realizada hoje.",
                'tipo' => 'warning',
                'icon' => 'exclamation-triangle',
            ],
            'atrasada' => [
                'titulo' => 'Manutenção Vencida',
                'mensagem' => "Crítico: A manutenção {$tipoManutencao} do veículo {$placa} está atrasada! Regularize imediatamente.",
                'tipo' => 'danger',
                'icon' => 'exclamation-circle',
            ],
            default => [
                'titulo' => 'Manutenção Pendente',
                'mensagem' => "O veículo {$placa} precisa realizar {$tipoManutencao}.",
                'tipo' => 'info',
                'icon' => 'wrench-screwdriver',
            ]
        };

        $config = match ($this->tipoAviso) {
            '1_mes' => ['titulo' => 'Planejamento', 'msg' => "Vence em 1 mês.", 'tipo' => 'info'],
            '1_semana' => ['titulo' => 'Próxima', 'msg' => "Vence em 1 semana.", 'tipo' => 'warning'],
            'hoje' => ['titulo' => 'É Hoje', 'msg' => "Vence hoje!", 'tipo' => 'warning'],
            'atrasada' => ['titulo' => 'Vencida', 'msg' => "Está atrasada!", 'tipo' => 'danger'],
            default => ['titulo' => 'Pendente', 'msg' => "Manutenção necessária.", 'tipo' => 'info'],
        };

        // Sobrescrever com textos mais descritivos
        $titulo = match ($this->tipoAviso) {
            '1_mes' => 'Lembrete de Manutenção',
            '1_semana' => 'Manutenção Próxima',
            'hoje' => 'Manutenção Vence Hoje',
            'atrasada' => 'Manutenção Atrasada',
            default => 'Manutenção Pendente'
        };

        $mensagem = match ($this->tipoAviso) {
            '1_mes' => "Planejamento: {$tipoManutencao} do {$placa} em 30 dias.",
            '1_semana' => "Atenção: {$tipoManutencao} do {$placa} em 7 dias.",
            'hoje' => "Hoje: {$tipoManutencao} do {$placa} vence hoje.",
            'atrasada' => "Atraso: {$tipoManutencao} do {$placa} está vencida.",
            default => "O veículo {$placa} precisa de {$tipoManutencao}."
        };
        
        $tipo = match ($this->tipoAviso) {
            'atrasada' => 'danger',
            'hoje', '1_semana' => 'warning',
            default => 'info'
        };

        $icon = match ($this->tipoAviso) {
            'atrasada', 'hoje' => 'exclamation-circle', // Vamos usar exclamation-circle para criticos
            '1_semana' => 'clock', // clock nao temos no frontend mapping, vamos usar wrench-screwdriver standard ou adicionar suporte dps. Vamos manter wrench-screwdriver para tudo por enqt para nao quebrar icones, mas mudar a cor resolve.
            default => 'wrench-screwdriver'
        };

        // Vamos simplificar e usar wrench-screwdriver para tudo, diferenciando pela cor (tipo)
        // O usuario pediu "rules", nao icones novos necessariamente.
        
        return [
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'link' => route('manutencoes.edit', $this->manutencao->man_id, false),
            'tipo' => $tipo,
            'icon' => 'wrench-screwdriver', // Manter padrao seguro
            'data_evento' => now()->toISOString(),
        ];
    }
}
