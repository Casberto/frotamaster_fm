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
            $table->string('placa', 7);
            $table->string('marca');
            $table->string('modelo');
            $table->year('ano_fabricacao');
            $table->year('ano_modelo');
            $table->string('cor')->nullable();
            $table->string('chassi')->nullable();
            $table->string('renavam')->nullable();
            $table->enum('tipo_veiculo', ['carro', 'moto', 'caminhao', 'van', 'outro']);
            $table->enum('tipo_combustivel', ['gasolina', 'etanol', 'diesel', 'flex', 'gnv', 'eletrico']);
            $table->integer('quilometragem_atual');
            $table->date('data_aquisicao')->nullable();
            $table->enum('status', ['ativo', 'inativo', 'em_manutencao', 'vendido'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // --- CORREÇÃO APLICADA AQUI ---
            // Define que a combinação de empresa + campo deve ser única.
            $table->unique(['id_empresa', 'placa']);
            $table->unique(['id_empresa', 'chassi']);
            $table->unique(['id_empresa', 'renavam']);
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
