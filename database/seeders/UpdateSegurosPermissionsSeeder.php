<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;

class UpdateSegurosPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing Seguros permissions to standard naming
        Permissao::where('prm_codigo', 'SEG002')->update(['prm_acao' => 'Criar']);
        Permissao::where('prm_codigo', 'SEG003')->update(['prm_acao' => 'Editar']);
        Permissao::where('prm_codigo', 'SEG004')->update(['prm_acao' => 'Excluir']);

        $permissoes = [
             // Sub-ações Seguros (Coberturas) - already added but ensuring descriptions match standard if needed
            // ['prm_codigo' => 'SEG005', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir cobertura'],
            // ['prm_codigo' => 'SEG006', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Excluir cobertura'],
            // ['prm_codigo' => 'SEG007', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir imagens na apolice'],

            // --- Sinistros ---
            ['prm_codigo' => 'SEG008', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir sinistro', 'prm_descricao' => 'Permite registrar novos sinistros.'],
            ['prm_codigo' => 'SEG009', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Editar sinistro', 'prm_descricao' => 'Permite editar sinistros existentes.'],
            ['prm_codigo' => 'SEG010', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Excluir sinistro', 'prm_descricao' => 'Permite excluir sinistros.'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::firstOrCreate(
                ['prm_codigo' => $permissao['prm_codigo']],
                $permissao
            );
        }
    }
}
