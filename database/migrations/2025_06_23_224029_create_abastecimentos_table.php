<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abastecimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_veiculo')->constrained('veiculos')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->date('data_abastecimento');
            $table->integer('quilometragem');
            $table->string('unidade_medida')->default('litros'); // Litros, kWh, etc.
            $table->decimal('quantidade', 8, 3); // Nome genérico para litros ou kWh
            $table->decimal('valor_por_unidade', 8, 3);
            $table->decimal('custo_total', 10, 2);
            $table->string('nome_posto')->nullable();
            $table->string('tipo_combustivel')->nullable();
            $table->string('nivel_tanque_inicio')->nullable(); // Ex: '1/4', '1/2', etc.
            $table->boolean('tanque_cheio')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abastecimentos');
    }
};
