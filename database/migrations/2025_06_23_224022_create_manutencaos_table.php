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
        // 1. Criar a nova tabela de fornecedores
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id('for_id');
            $table->foreignId('for_emp_id')->constrained('empresas')->onDelete('cascade');
            $table->string('for_nome_fantasia');
            $table->string('for_razao_social')->nullable();
            $table->string('for_cnpj_cpf')->nullable();
            $table->string('for_contato_email')->nullable();
            $table->string('for_contato_telefone')->nullable();
            $table->text('for_endereco')->nullable();
            $table->enum('for_tipo', ['oficina', 'posto', 'ambos', 'outro'])->default('outro')->comment('Tipo do fornecedor: Oficina, Posto de Combustível, Ambos, ou Outro.');
            $table->tinyInteger('for_status')->default(1)->comment('1-Ativo, 2-Inativo');
            $table->text('for_observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Criar a nova tabela de serviços (catálogo de serviços por empresa)
        Schema::create('servicos', function (Blueprint $table) {
            $table->id('ser_id');
            $table->foreignId('ser_emp_id')->constrained('empresas')->onDelete('cascade');
            $table->string('ser_nome');
            $table->text('ser_descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Renomear a tabela antiga de manutenções para evitar conflitos (opcional, mas seguro)
        if (Schema::hasTable('manutencoes')) {
            Schema::rename('manutencoes', 'manutencoes_old');
        }

        // 4. Criar a nova tabela de manutenções refatorada
        Schema::create('manutencoes', function (Blueprint $table) {
            $table->id('man_id');
            // CORREÇÃO: Aponta explicitamente para a tabela 'veiculos' e a coluna 'vei_id'
            $table->foreignId('man_vei_id')->constrained(table: 'veiculos', column: 'vei_id')->onDelete('cascade');
            $table->foreignId('man_emp_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('man_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('man_for_id')->nullable()->constrained('fornecedores', 'for_id')->onDelete('set null');

            $table->enum('man_tipo', ['preventiva', 'corretiva', 'preditiva', 'outra']);
            $table->date('man_data_inicio');
            $table->date('man_data_fim')->nullable();
            $table->unsignedInteger('man_km');
            
            $table->decimal('man_custo_previsto', 10, 2)->nullable();
            $table->decimal('man_custo_pecas', 10, 2)->nullable();
            $table->decimal('man_custo_mao_de_obra', 10, 2)->nullable();
            $table->decimal('man_custo_total', 10, 2)->default(0);

            $table->string('man_responsavel')->nullable();
            $table->string('man_nf')->nullable();
            $table->text('man_observacoes')->nullable();
            
            $table->date('man_prox_revisao_data')->nullable();
            $table->unsignedInteger('man_prox_revisao_km')->nullable();
            
            $table->enum('man_status', ['agendada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            
            $table->timestamps();
        });

        // 5. Criar a tabela pivô para ligar manutenções e serviços
        Schema::create('manutencao_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ms_man_id')->constrained('manutencoes', 'man_id')->onDelete('cascade');
            $table->foreignId('ms_ser_id')->constrained('servicos', 'ser_id')->onDelete('cascade');
            $table->decimal('ms_custo', 10, 2)->default(0);
            $table->date('ms_garantia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutencao_servico');
        Schema::dropIfExists('manutencoes');
        Schema::dropIfExists('servicos');
        Schema::dropIfExists('fornecedores');
        
        if (Schema::hasTable('manutencoes_old')) {
            Schema::rename('manutencoes_old', 'manutencoes');
        }
    }
};

