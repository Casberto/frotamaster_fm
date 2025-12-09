<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;

class UpdateDashboardPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            ['prm_codigo' => 'DAS001', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Geral', 'prm_descricao' => 'Permite visualizar o dashboard principal e indicadores gerais.'],
            ['prm_codigo' => 'DAS002', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Aba Veículos', 'prm_descricao' => 'Permite visualizar a aba de resumo de veículos no dashboard.'],
            ['prm_codigo' => 'DAS003', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Aba Motoristas', 'prm_descricao' => 'Permite visualizar a aba de resumo de motoristas no dashboard.'],
            ['prm_codigo' => 'DAS004', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Aba Manutenções', 'prm_descricao' => 'Permite visualizar a aba de resumo de manutenções no dashboard.'],
            ['prm_codigo' => 'DAS005', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Aba Abastecimentos', 'prm_descricao' => 'Permite visualizar a aba de resumo de abastecimentos no dashboard.'],
            ['prm_codigo' => 'DAS006', 'prm_modulo' => 'Dashboard', 'prm_acao' => 'Visualizar Aba Reservas', 'prm_descricao' => 'Permite visualizar a aba de resumo de reservas no dashboard.'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::updateOrCreate(
                ['prm_codigo' => $permissao['prm_codigo']],
                [
                    'prm_modulo' => $permissao['prm_modulo'],
                    'prm_acao' => $permissao['prm_acao'],
                    'prm_descricao' => $permissao['prm_descricao']
                ]
            );
        }
    }
}
