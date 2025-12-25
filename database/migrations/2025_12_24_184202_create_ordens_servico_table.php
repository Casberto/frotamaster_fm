<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id('osv_id');
            $table->unsignedBigInteger('osv_emp_id');
            $table->unsignedBigInteger('osv_vct_id'); // Veículo
            $table->string('osv_codigo', 20)->unique(); // Ex: OS-2025-001
            
            // Status do Kanban
            $table->enum('osv_status', ['aguardando', 'diagnostico', 'aprovacao', 'aprovado', 'pecas', 'execucao', 'pronto', 'entregue'])->default('aguardando');
            
            // Prioridade Visual
            $table->enum('osv_prioridade', ['baixa', 'normal', 'alta', 'urgente'])->default('normal');
        
            $table->text('osv_problema_relatado')->nullable(); // O que o cliente disse
            $table->text('osv_diagnostico_tecnico')->nullable(); // O que o mecânico achou
            
            // Checkpoints do Checklist (JSON para ser flexível e rápido)
            $table->json('osv_checklist_entrada')->nullable();
            
            // Financeiro Macro da OS
            $table->decimal('osv_valor_pecas', 10, 2)->default(0);
            $table->decimal('osv_valor_mao_obra', 10, 2)->default(0);
            $table->decimal('osv_valor_total', 10, 2)->default(0);
            $table->decimal('osv_valor_custo_total', 10, 2)->default(0); // Para cálculo de lucro
            
            // Token para link público (UUID)
            $table->uuid('osv_token_acesso')->unique(); 
            
            $table->dateTime('osv_data_entrada');
            $table->dateTime('osv_previsao_entrega')->nullable();
            $table->dateTime('osv_data_saida')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('osv_emp_id')->references('id')->on('empresas');
            $table->foreign('osv_vct_id')->references('vct_id')->on('veiculos_terceiros');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
