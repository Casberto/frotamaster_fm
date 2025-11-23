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
        Schema::create('reservas', function (Blueprint $table) {
            $table->bigIncrements('res_id'); // ID Interno do Sistema (PK)
            
            $table->foreignId('res_emp_id')->comment('ID da Empresa (FK de empresas)')->constrained('empresas', 'id')->onDelete('cascade');
            $table->unsignedBigInteger('res_codigo')->comment('ID Sequencial visível para a empresa'); // NOVO CAMPO: Sequencial por empresa
            
            $table->foreignId('res_vei_id')->nullable()->comment('ID do Veículo (Pode ser null se for "A definir")')->constrained('veiculos', 'vei_id')->onDelete('cascade');
            $table->foreignId('res_sol_id')->comment('ID do Solicitante (FK de users)')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('res_mot_id')->nullable()->comment('ID do Motorista (FK de motoristas)')->constrained('motoristas', 'mot_id')->onDelete('set null');
            $table->foreignId('res_for_id')->nullable()->comment('ID do Fornecedor (FK de fornecedores)')->constrained('fornecedores', 'for_id')->onDelete('set null');
            
            $table->string('res_tipo', 50)->comment('Tipo de reserva (viagem | manutencao)');
            $table->dateTime('res_data_inicio')->comment('Data/hora de início da reserva');
            $table->dateTime('res_data_fim')->comment('Data/hora de término da reserva');
            $table->boolean('res_dia_todo')->default(false)->comment('Flag se a reserva é para o dia todo');
            
            $table->string('res_origem')->nullable()->comment('Local de origem (para viagens)');
            $table->string('res_destino')->nullable()->comment('Local de destino (para viagens)');
            $table->text('res_just')->nullable()->comment('Justificativa da reserva/viagem');
            $table->text('res_obs')->nullable()->comment('Observações gerais (ex: motivo rejeição/cancelamento)');
            
            $table->string('res_status', 50)->default('pendente')->comment('Status do fluxo (pendente, aprovada, em_uso, em_revisao, encerrada, rejeitada, cancelada)');
            
            $table->integer('res_km_inicio')->unsigned()->nullable()->comment('KM do veículo na saída');
            $table->integer('res_km_fim')->unsigned()->nullable()->comment('KM do veículo no retorno');
            $table->timestamp('res_hora_saida')->nullable()->comment('Data e hora efetiva da saída');
            $table->timestamp('res_hora_chegada')->nullable()->comment('Data e hora efetiva do retorno');
            $table->string('res_comb_inicio', 50)->nullable()->comment('Nível de combustível na saída (ex: 1/4, 1/2, 3/4, cheio)');
            $table->string('res_comb_fim', 50)->nullable()->comment('Nível de combustível no retorno (ex: 1/4, 1/2, 3/4, cheio)');

            $table->foreignId('res_revisor_id')->nullable()->comment('ID do usuário que revisou/encerrou')->constrained('users', 'id')->onDelete('set null');
            $table->timestamp('res_data_revisao')->nullable()->comment('Data e hora da revisão/encerramento');
            $table->text('res_obs_revisor')->nullable()->comment('Observações do revisor');
            $table->text('res_obs_finais')->nullable()->comment('Observações do motorista ao finalizar');
            
            $table->foreignId('created_by')->nullable()->comment('ID do Usuário que criou (FK de users)')->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->comment('ID do Usuário que atualizou (FK de users)')->constrained('users', 'id')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Índices para performance
            $table->index('res_emp_id');
            $table->index('res_vei_id');
            $table->index('res_status');
            $table->index(['res_data_inicio', 'res_data_fim']); // Índice composto para busca por período
            
            // Garante que o código é único dentro da empresa
            $table->unique(['res_emp_id', 'res_codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};