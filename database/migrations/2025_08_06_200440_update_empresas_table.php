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
        Schema::table('empresas', function (Blueprint $table) {
            // Remove as colunas 'status_pagamento' e 'data_vencimento_plano'
            $table->dropColumn(['status_pagamento', 'data_vencimento_plano']);

            // Adiciona novas colunas para informações de endereço, todas opcionais (nullable)
            $table->string('cep')->nullable()->after('telefone_contato'); // CEP da empresa
            $table->string('logradouro')->nullable()->after('cep'); // Logradouro (rua, avenida, etc.)
            $table->string('numero')->nullable()->after('logradouro'); // Número do endereço
            $table->string('complemento')->nullable()->after('numero'); // Complemento do endereço
            $table->string('bairro')->nullable()->after('complemento'); // Bairro
            $table->string('cidade')->nullable()->after('bairro'); // Cidade
            $table->string('estado')->nullable()->after('cidade'); // Estado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('empresas', function (Blueprint $table) {
            // Adiciona de volta as colunas removidas (se necessário para rollback)
            $table->string('status_pagamento')->default('ativo');
            $table->date('data_vencimento_plano')->nullable();

            // Remove as colunas de endereço adicionadas
            $table->dropColumn(['cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'estado']);
        });
    }
};
