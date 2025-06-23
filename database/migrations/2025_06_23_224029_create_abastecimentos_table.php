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
            $table->foreignId('id_veiculo')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->date('data_abastecimento');
            $table->integer('quilometragem');
            $table->enum('tipo_combustivel', ['gasolina', 'etanol', 'diesel', 'gnv']);
            $table->decimal('litros', 8, 3);
            $table->decimal('valor_por_litro', 8, 3);
            $table->decimal('custo_total', 10, 2);
            $table->string('nome_posto')->nullable();
            $table->boolean('tanque_cheio')->default(false);
            $table->timestamps();
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
