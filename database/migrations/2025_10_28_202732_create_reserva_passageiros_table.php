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
        Schema::create('reservas_passageiros', function (Blueprint $table) {
            $table->bigIncrements('rpa_id');
            $table->foreignId('rpa_res_id')->comment('ID da Reserva (FK de reservas)')->constrained('reservas', 'res_id')->onDelete('cascade');

            $table->string('rpa_nome')->comment('Nome do passageiro');
            $table->string('rpa_doc', 50)->nullable()->comment('Documento do passageiro');
            $table->string('rpa_entrou_em')->nullable()->comment('Local ou ponto de embarque');
            $table->string('rpa_saiu_em')->nullable()->comment('Local ou ponto de desembarque');
            
            $table->timestamps();

            $table->index('rpa_res_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_passageiros');
    }
};
