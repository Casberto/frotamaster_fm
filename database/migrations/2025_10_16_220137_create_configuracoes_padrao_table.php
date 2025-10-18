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
        Schema::create('configuracoes_padrao', function (Blueprint $table) {
            $table->id('cfp_id');
            $table->string('cfp_modulo', 50);
            $table->string('cfp_chave', 100);
            $table->text('cfp_valor');
            $table->string('cfp_tipo', 20)->comment('boolean, integer, string, text');
            $table->string('cfp_descricao', 255);
            $table->timestamps();

            // Adiciona um índice único para garantir que não haja chaves duplicadas no mesmo módulo.
            $table->unique(['cfp_modulo', 'cfp_chave']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes_padrao');
    }
};
