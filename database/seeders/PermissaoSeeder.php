<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permissao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilita a verificação de chaves estrangeiras para permitir o truncate
        Schema::disableForeignKeyConstraints();

        // Limpa a tabela antes de popular para evitar duplicatas em re-execuções
        DB::table('permissoes')->truncate();

        // Reabilita a verificação de chaves estrangeiras
        Schema::enableForeignKeyConstraints();

        $permissoes = [
            // Módulo Veículos
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de veículos.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite cadastrar novos veículos.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar informações de veículos existentes.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir veículos.'],

            // Módulo Manutenções
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de manutenções.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite registrar novas manutenções.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar manutenções existentes.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir registros de manutenção.'],

            // Módulo Abastecimentos
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de abastecimentos.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite registrar novos abastecimentos.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar abastecimentos existentes.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir registros de abastecimento.'],

            // Módulo Perfis
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de perfis de usuário.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite criar novos perfis de usuário.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar perfis de usuário existentes.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir perfis de usuário.'],

            // Módulo Fornecedores
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de fornecedores.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite cadastrar novos fornecedores.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar fornecedores existentes.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir fornecedores.'],

            // Módulo Serviços
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'visualizar', 'prm_descricao' => 'Permite visualizar a lista de serviços.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'criar', 'prm_descricao' => 'Permite cadastrar novos serviços.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'editar', 'prm_descricao' => 'Permite editar serviços existentes.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'excluir', 'prm_descricao' => 'Permite excluir serviços.'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::create($permissao);
        }
    }
}