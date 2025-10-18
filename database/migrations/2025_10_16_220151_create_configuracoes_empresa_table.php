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
        Schema::create('configuracoes_empresas', function (Blueprint $table) {
            $table->id('cfe_id');
            $table->foreignId('cfe_emp_id')->constrained('empresas', 'id')->onDelete('cascade');
            $table->foreignId('cfe_cfp_id')->constrained('configuracoes_padrao', 'cfp_id')->onDelete('cascade');
            $table->text('cfe_valor');
            $table->timestamps();

            // Adiciona um índice único para garantir que cada empresa só possa ter um valor por configuração padrão.
            $table->unique(['cfe_emp_id', 'cfe_cfp_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes_empresas');
    }
};
