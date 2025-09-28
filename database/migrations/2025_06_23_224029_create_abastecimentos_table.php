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
        Schema::create('abastecimentos', function (Blueprint $table) {
            $table->id();

            // CORREÇÃO: Aponta explicitamente para a tabela 'veiculos' e a coluna 'vei_id'
            $table->foreignId('id_veiculo')->constrained(table: 'veiculos', column: 'vei_id')->onDelete('cascade');

            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->unsignedBigInteger('id_user'); // Adicionado
            $table->date('data_abastecimento');
            $table->integer('quilometragem');
            $table->string('unidade_medida');
            $table->decimal('quantidade', 10, 3);
            $table->decimal('valor_por_unidade', 10, 3);
            $table->decimal('custo_total', 10, 2);
            $table->string('nome_posto')->nullable();
            $table->string('tipo_combustivel');
            $table->string('nivel_tanque_inicio')->nullable();
            $table->boolean('tanque_cheio')->default(false);
            $table->timestamps();

            // Adicionando a chave estrangeira para id_user de forma explícita
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abastecimentos');
    }
};
