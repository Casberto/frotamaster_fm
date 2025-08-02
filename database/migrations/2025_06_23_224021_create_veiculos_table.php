<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            
            // Dados Principais
            $table->string('placa', 10);
            $table->string('marca');
            $table->string('modelo');
            $table->string('ano_fabricacao', 4);
            $table->string('ano_modelo', 4);
            $table->string('cor');
            
            // Documentação
            $table->string('chassi')->unique();
            $table->string('renavam')->unique();
            
            // Detalhes Operacionais
            $table->string('tipo_veiculo'); // Ex: Carro, Moto, Caminhão
            $table->string('tipo_combustivel'); // Ex: Gasolina, Diesel, Flex, Elétrico
            $table->date('data_aquisicao')->nullable();
            $table->integer('quilometragem_inicial');
            $table->integer('quilometragem_atual');
            $table->decimal('capacidade_tanque', 8, 2)->nullable()->comment('Em litros ou kWh para elétricos');
            
            // --- Campos Relacionados a Consumo Médio ---
            $table->decimal('consumo_medio_fabricante', 8, 2)->nullable()->comment('Consumo em KM/L ou KM/kWh informado pela fabricante.');
            $table->decimal('consumo_medio_atual', 8, 2)->nullable()->comment('Consumo médio calculado pelo sistema com base nos abastecimentos.');
            $table->boolean('alerta_consumo_ativo')->default(false)->comment('Flag que indica se o consumo está anormal.');
            
            // --- Campos gerenciais ---
            $table->string('status'); // Ex: Ativo, Inativo, Em Manutenção
            $table->text('observacoes')->nullable();
            
            $table->timestamps();

            // Garantir que a placa seja única por empresa
            $table->unique(['placa', 'id_empresa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
