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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            
            // Seção 1: Identificação do Veículo
            $table->string('placa', 8);
            $table->string('marca');
            $table->string('modelo');
            $table->string('cor');
            $table->year('ano_fabricacao');
            $table->year('ano_modelo');
            $table->string('tipo_veiculo'); // Ex: Carro, Moto, Caminhão

            // Seção 2: Documentação
            $table->string('chassi', 17)->nullable();
            $table->string('renavam', 11)->nullable();
            $table->date('vencimento_licenciamento')->nullable();

            // Seção 3: Detalhes Operacionais
            $table->integer('quilometragem_inicial')->default(0);
            $table->integer('quilometragem_atual')->default(0);
            $table->string('tipo_combustivel'); // Ex: Gasolina, Diesel, Flex, Elétrico
            $table->decimal('capacidade_tanque', 8, 2)->nullable()->comment('Em litros ou kWh para elétricos');
            
            // Seção 4: Seguro e Pneus
            $table->string('seguradora')->nullable();
            $table->string('apolice_seguro')->nullable();
            $table->date('vencimento_apolice')->nullable();
            $table->integer('km_troca_pneus')->nullable();
            $table->date('data_troca_pneus')->nullable();

            // Seção 5: Status e Aquisição
            $table->date('data_aquisicao')->nullable();
            $table->string('status')->default('ativo'); // Ex: Ativo, Inativo, Em Manutenção
            $table->text('observacoes')->nullable();
            
            $table->timestamps();

            // Índices para garantir que os dados sejam únicos por empresa
            $table->unique(['placa', 'id_empresa']);
            $table->unique(['chassi', 'id_empresa']);
            $table->unique(['renavam', 'id_empresa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
