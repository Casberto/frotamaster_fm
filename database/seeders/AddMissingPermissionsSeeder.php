<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;

class AddMissingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            // --- Módulo Parâmetros ---
            ['prm_codigo' => 'PAR001', 'prm_modulo' => 'Parâmetros', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar parâmetros do sistema.'],
            ['prm_codigo' => 'PAR003', 'prm_modulo' => 'Parâmetros', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar e salvar parâmetros.'],

            // --- Módulo Seguros ---
            ['prm_codigo' => 'SEG001', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar apólices de seguro.'],
            ['prm_codigo' => 'SEG002', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir apolice', 'prm_descricao' => 'Permite cadastrar novas apólices.'],
            ['prm_codigo' => 'SEG003', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Editar apolice', 'prm_descricao' => 'Permite editar apólices existentes.'],
            ['prm_codigo' => 'SEG004', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Excluir apolice', 'prm_descricao' => 'Permite excluir apólices.'],
            
            // Sub-ações Seguros
            ['prm_codigo' => 'SEG005', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir cobertura', 'prm_descricao' => 'Permite adicionar coberturas à apólice.'],
            ['prm_codigo' => 'SEG006', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Excluir cobertura', 'prm_descricao' => 'Permite remover coberturas da apólice.'],
            ['prm_codigo' => 'SEG007', 'prm_modulo' => 'Seguros', 'prm_acao' => 'Incluir imagens na apolice', 'prm_descricao' => 'Permite fazer upload de arquivos/imagens na apólice.'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::firstOrCreate(
                ['prm_codigo' => $permissao['prm_codigo']],
                $permissao
            );
        }
    }
}
