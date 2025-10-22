<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Executa as migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $now = Carbon::now();

        $parametros = [
            // --- Dados Pessoais ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_apelido', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Apelido" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_data_nascimento', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Nascimento" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_genero', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Gênero" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_nacionalidade', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Nacionalidade" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_estado_civil', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Estado Civil" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_nome_mae', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Nome da Mãe" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_nome_pai', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Nome do Pai" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            
            // --- Documentos ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cpf', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "CPF" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_rg', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "RG" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_orgao_emissor_rg', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Órgão Emissor do RG" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_data_emissao_rg', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Emissão do RG" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_pis', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "PIS" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_ctps_numero', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Número da CTPS" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_ctps_serie', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Série da CTPS" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_titulo_eleitor', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Título de Eleitor" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_zona_eleitoral', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Zona Eleitoral" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_secao_eleitoral', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Seção Eleitoral" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],

            // --- CNH ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_numero', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Número da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_categoria', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Categoria da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_data_emissao', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Emissão da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_data_validade', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Validade da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_primeira_habilitacao', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Primeira Habilitação" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_uf', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "UF da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cnh_observacoes', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Observações da CNH" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'bloqueia_cnh_vencida', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Bloqueia a atividade de motoristas com CNH vencida', 'created_at' => $now, 'updated_at' => $now],
            
            // --- Contato ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_email', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Email" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_telefone1', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Telefone 1" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_telefone2', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Telefone 2" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],

            // --- Endereço ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cep', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "CEP" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_endereco', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Endereço" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_numero', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Número" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_complemento', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Complemento" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_bairro', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Bairro" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_cidade', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Cidade" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_estado', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Estado" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],

            // --- Dados Profissionais ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_data_admissao', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Admissão" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_data_demissao', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Data de Demissão" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_tipo_contrato', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Tipo de Contrato" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_categoria_profissional', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Categoria Profissional" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_matricula_interna', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Matrícula Interna" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_observacoes', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Observações" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],

            // --- Dados Bancários ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_banco', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Banco" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_agencia', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Agência" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_conta', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Conta" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_tipo_conta', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Tipo de Conta" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_chave_pix', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Define se o campo "Chave PIX" deve ser informado.', 'created_at' => $now, 'updated_at' => $now],
       
            // --- Regras ---
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'bloqueia_cnh_vencida', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Bloqueia a atividade de motoristas com CNH vencida?', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'usar_usuario', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Exige vinculação com usuário?', 'created_at' => $now, 'updated_at' => $now],
            ['cfp_modulo' => 'motoristas', 'cfp_chave' => 'exige_cnh', 'cfp_valor' => '1', 'cfp_tipo' => 'boolean', 'cfp_descricao' => 'Exige CNH? (Habilita ou Desabilita a inclusão dos dados de CNH)', 'created_at' => $now, 'updated_at' => $now],
            
        ];

        DB::table('configuracoes_padrao')->insert($parametros);
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Remove todos os parâmetros que foram adicionados para o módulo de motoristas
        DB::table('configuracoes_padrao')->where('cfp_modulo', 'motoristas')->delete();
    }
};
