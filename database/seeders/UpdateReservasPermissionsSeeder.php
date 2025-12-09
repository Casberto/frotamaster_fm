<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;

class UpdateReservasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            ['prm_codigo' => 'RES001', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de reservas.'],
            ['prm_codigo' => 'RES002', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Criar reserva de viagem', 'prm_descricao' => 'Permite criar novas reservas de viagem.'],
            ['prm_codigo' => 'RES003', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar reservas existentes.'],
            ['prm_codigo' => 'RES004', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir ou cancelar reservas.'],
            ['prm_codigo' => 'RES005', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Registrar saída', 'prm_descricao' => 'Permite registrar o início da viagem (saída).'],
            ['prm_codigo' => 'RES006', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Finalizar', 'prm_descricao' => 'Permite finalizar a reserva (retorno).'],
            ['prm_codigo' => 'RES007', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Aprovar', 'prm_descricao' => 'Permite aprovar solicitações de reserva.'],
            ['prm_codigo' => 'RES008', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Reprovar', 'prm_descricao' => 'Permite reprovar solicitações de reserva.'],
            ['prm_codigo' => 'RES009', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Encerrar', 'prm_descricao' => 'Permite realizar a revisão e encerrar o processo (Revisar).'],
            ['prm_codigo' => 'RES010', 'prm_modulo' => 'Reservas', 'prm_acao' => 'Criar reserva de manutenção', 'prm_descricao' => 'Permite criar reservas específicas para manutenção.'],
        ];

        foreach ($permissoes as $permissao) {
            // Update or Create based on Code
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
