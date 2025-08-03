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
        Schema::create('manutencoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_veiculo')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->enum('tipo_manutencao', ['preventiva', 'corretiva', 'preditiva', 'outra']);
            $table->string('descricao_servico');
            $table->date('data_manutencao');
            $table->integer('quilometragem');
            $table->decimal('custo_total', 10, 2);
            $table->decimal('custo_previsto', 10, 2)->nullable();
            $table->string('nome_fornecedor')->nullable();
            $table->string('responsavel')->nullable();
            $table->text('observacoes')->nullable();
            $table->date('proxima_revisao_data')->nullable();
            $table->integer('proxima_revisao_km')->nullable();            
            $table->enum('status', ['agendada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutencoes');
    }
};
