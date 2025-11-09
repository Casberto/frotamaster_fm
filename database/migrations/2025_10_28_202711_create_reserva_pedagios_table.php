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
        Schema::create('reserva_pedagios', function (Blueprint $table) {
            $table->bigIncrements('rpe_id');
            $table->foreignId('rpe_res_id')->comment('ID da Reserva (FK de reservas)')->constrained('reservas', 'res_id')->onDelete('cascade');
            
            $table->string('rpe_desc')->nullable()->comment('Descrição ou local do pedágio');
            $table->decimal('rpe_valor', 10, 2)->comment('Valor pago no pedágio');
            $table->string('rpe_forma_pagto', 50)->nullable()->comment('Forma de pagamento (TAG, Dinheiro, etc)');
            $table->boolean('rpe_reembolso')->default(false)->comment('Flag se este pedágio é para reembolso');
            $table->dateTime('rpe_data_hora')->nullable()->comment('Data e hora da passagem');
            
            $table->timestamps();

            $table->index('rpe_res_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_pedagios');
    }
};
