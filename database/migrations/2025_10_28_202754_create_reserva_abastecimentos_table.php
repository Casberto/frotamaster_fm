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
        Schema::create('reserva_abastecimentos', function (Blueprint $table) {
            $table->bigIncrements('rab_id');
            
            $table->foreignId('rab_res_id')->comment('ID da Reserva (FK de reservas)')->constrained('reservas', 'res_id')->onDelete('cascade');
            $table->foreignId('rab_abs_id')->comment('ID do Abastecimento (FK de abastecimentos)')->constrained('abastecimentos', 'aba_id')->onDelete('cascade');
            $table->foreignId('rab_mot_id')->nullable()->comment('ID do Motorista (FK de motoristas) que registrou')->constrained('motoristas', 'mot_id')->onDelete('set null');
            $table->foreignId('rab_emp_id')->comment('ID da Empresa (FK de empresas)')->constrained('empresas', 'id')->onDelete('cascade');

            $table->string('rab_forma_pagto', 50)->nullable()->comment('Forma de pagamento (TAG, Dinheiro, etc)');
            $table->boolean('rab_reembolso')->default(false)->comment('Flag se este abastecimento é para reembolso');
            
            $table->timestamp('created_at')->nullable();

            $table->index('rab_res_id');
            $table->index('rab_abs_id');
            $table->index('rab_emp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_abastecimentos');
    }
};
