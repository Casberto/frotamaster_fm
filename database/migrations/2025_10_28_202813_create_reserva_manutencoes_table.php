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
        // Tabela pivô para vincular manutenções a uma reserva (Seção 3.4)
        Schema::create('reserva_manutencoes', function (Blueprint $table) {
            $table->bigIncrements('rma_id'); // Usei 'rma' como prefixo para Reserva-Manutencao
            
            $table->foreignId('rma_res_id')->comment('ID da Reserva (FK de reservas)')->constrained('reservas', 'res_id')->onDelete('cascade');
            $table->foreignId('rma_man_id')->comment('ID da Manutenção (FK de manutencoes)')->constrained('manutencoes', 'man_id')->onDelete('cascade');
            $table->foreignId('rma_mot_id')->nullable()->comment('ID do Motorista (FK de motoristas) que registrou')->constrained('motoristas', 'mot_id')->onDelete('set null');
            $table->foreignId('rma_emp_id')->comment('ID da Empresa (FK de empresas)')->constrained('empresas', 'id')->onDelete('cascade');
            
            $table->timestamp('created_at')->nullable();

            $table->index('rma_res_id');
            $table->index('rma_man_id');
            $table->index('rma_emp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_manutencoes');
    }
};
