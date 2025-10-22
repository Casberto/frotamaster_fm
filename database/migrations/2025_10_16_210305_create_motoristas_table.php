<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motoristas', function (Blueprint $table) {
            $table->id('mot_id');
            $table->foreignId('mot_emp_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('mot_user_id')->nullable()->constrained('users')->onDelete('set null');

            // Dados pessoais
            $table->string('mot_nome', 150);
            $table->string('mot_apelido', 80)->nullable();
            $table->date('mot_data_nascimento')->nullable();
            $table->string('mot_genero', 20)->nullable();
            $table->string('mot_nacionalidade', 50)->nullable();
            $table->string('mot_estado_civil', 30)->nullable();

            // Filiação
            $table->string('mot_nome_mae', 150)->nullable();
            $table->string('mot_nome_pai', 150)->nullable();

            // Documentos
            $table->string('mot_cpf', 14)->nullable()->unique();
            $table->string('mot_rg', 20)->nullable();
            $table->string('mot_orgao_emissor_rg', 20)->nullable();
            $table->date('mot_data_emissao_rg')->nullable();
            $table->string('mot_pis', 20)->nullable();
            $table->string('mot_ctps_numero', 20)->nullable();
            $table->string('mot_ctps_serie', 20)->nullable();
            $table->string('mot_titulo_eleitor', 20)->nullable();
            $table->string('mot_zona_eleitoral', 10)->nullable();
            $table->string('mot_secao_eleitoral', 10)->nullable();

            // CNH
            $table->string('mot_cnh_numero', 20)->nullable();
            $table->string('mot_cnh_categoria', 10)->nullable();
            $table->date('mot_cnh_data_emissao')->nullable();
            $table->date('mot_cnh_data_validade')->nullable();
            $table->date('mot_cnh_primeira_habilitacao')->nullable();
            $table->string('mot_cnh_uf', 2)->nullable();
            $table->string('mot_cnh_observacoes', 255)->nullable();

            // Contato
            $table->string('mot_email', 120)->nullable();
            $table->string('mot_telefone1', 20)->nullable();
            $table->string('mot_telefone2', 20)->nullable();

            // Endereço
            $table->string('mot_cep', 10)->nullable();
            $table->string('mot_endereco', 150)->nullable();
            $table->string('mot_numero', 10)->nullable();
            $table->string('mot_complemento', 50)->nullable();
            $table->string('mot_bairro', 80)->nullable();
            $table->string('mot_cidade', 100)->nullable();
            $table->string('mot_estado', 2)->nullable();

            // Dados profissionais
            $table->date('mot_data_admissao')->nullable();
            $table->date('mot_data_demissao')->nullable();
            $table->string('mot_tipo_contrato', 50)->nullable();
            $table->string('mot_categoria_profissional', 50)->nullable();
            $table->string('mot_matricula_interna', 50)->nullable();
            $table->text('mot_observacoes')->nullable();

            // Dados bancários
            $table->string('mot_banco', 100)->nullable();
            $table->string('mot_agencia', 10)->nullable();
            $table->string('mot_conta', 20)->nullable();
            $table->string('mot_tipo_conta', 20)->nullable();
            $table->string('mot_chave_pix', 100)->nullable();

            // Controle
            $table->string('mot_status', 50)->default('Ativo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motoristas');
    }
};
