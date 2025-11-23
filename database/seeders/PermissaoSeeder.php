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
            // --- Módulo Veículos ---
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de veículos.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite cadastrar novos veículos.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar informações de veículos existentes.'],
            ['prm_modulo' => 'Veículos', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir veículos.'],

            // --- Módulo Manutenções ---
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de manutenções.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite registrar novas manutenções.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar manutenções existentes.'],
            ['prm_modulo' => 'Manutenções', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir registros de manutenção.'],

            // --- Módulo Abastecimentos ---
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de abastecimentos.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite registrar novos abastecimentos.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar abastecimentos existentes.'],
            ['prm_modulo' => 'Abastecimentos', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir registros de abastecimento.'],

            // --- Módulo Perfis ---
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de perfis de usuário.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite criar novos perfis de usuário.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar perfis de usuário existentes.'],
            ['prm_modulo' => 'Perfis', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir perfis de usuário.'],

            // --- Módulo Fornecedores ---
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de fornecedores.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite cadastrar novos fornecedores.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar fornecedores existentes.'],
            ['prm_modulo' => 'Fornecedores', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir fornecedores.'],

            // --- Módulo Serviços ---
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de serviços.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite cadastrar novos serviços.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar serviços existentes.'],
            ['prm_modulo' => 'Serviços', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir serviços.'],

            // --- Módulo Motoristas ---
            ['prm_modulo' => 'Motoristas', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de motoristas.'],
            ['prm_modulo' => 'Motoristas', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite cadastrar novos motoristas.'],
            ['prm_modulo' => 'Motoristas', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar motoristas existentes.'],
            ['prm_modulo' => 'Motoristas', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir motoristas.'],

            // --- Módulo Usuários ---
            ['prm_modulo' => 'Usuários', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de usuários.'],
            ['prm_modulo' => 'Usuários', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite cadastrar novos usuários.'],
            ['prm_modulo' => 'Usuários', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar usuários existentes.'],
            ['prm_modulo' => 'Usuários', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite excluir usuários.'],

            // --- Módulo Reservas (NOVO) ---
            // Ações Gerais (Motorista e Gerencial)
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Visualizar', 'prm_descricao' => 'Permite visualizar a lista de reservas (próprias ou todas, conforme regra).'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Criar', 'prm_descricao' => 'Permite solicitar ou criar novas reservas.'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Editar', 'prm_descricao' => 'Permite editar reservas (respeitando as regras de status).'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Excluir', 'prm_descricao' => 'Permite cancelar/excluir reservas pendentes.'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Registrar Saída', 'prm_descricao' => 'Permite registrar o início da viagem/uso do veículo.'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Finalizar', 'prm_descricao' => 'Permite finalizar o uso e enviar para revisão.'],
            
            // Ações Específicas (Apenas Gerencial deve ter acesso via Perfil)
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Aprovar', 'prm_descricao' => 'Permite aprovar solicitações de reservas pendentes.'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Reprovar', 'prm_descricao' => 'Permite reprovar solicitações de reservas.'],
            ['prm_modulo' => 'Reservas', 'prm_acao' => 'Encerrar', 'prm_descricao' => 'Permite validar a revisão e encerrar definitivamente a reserva.'],
        ];

        foreach ($permissoes as $permissao) {
            Permissao::create($permissao);
        }
    }
}