<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;
use App\Models\Perfil;

class NotificationPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar a permissão de notificações de manutenção
        $permissaoNotificacao = Permissao::firstOrCreate(
            ['prm_codigo' => 'NOT001'],
            [
                'prm_modulo' => 'Notificacoes',
                'prm_acao' => 'Receber Alertas',
                'prm_descricao' => 'Receber Alertas de Manutenção'
            ]
        );

        // 2. Encontrar a permissão de visualizar manutenção (MAN001) e associar
        $permissaoManutencao = Permissao::where('prm_codigo', 'MAN001')->first();

        if ($permissaoManutencao) {
            // Buscar perfis que têm a permissão MAN001
            $perfisManutencao = Perfil::whereHas('permissoes', function ($query) use ($permissaoManutencao) {
                $query->where('permissoes.prm_id', $permissaoManutencao->prm_id);
            })->get();

            // Atribuir a nova permissão para esses perfis
            foreach ($perfisManutencao as $perfil) {
                // syncWithoutDetaching garante que não duplicará se já existir
                $perfil->permissoes()->syncWithoutDetaching([$permissaoNotificacao->prm_id]);
            }
        }
    }
}
