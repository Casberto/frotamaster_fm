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
            $table->foreignId('id_user')->constrained('users')->comment('Usuário que registrou o abastecimento.');

            // Dados do Abastecimento
            $table->date('data_abastecimento');
            $table->integer('quilometragem');
            $table->string('unidade_medida'); // Litros, kWh, etc.
            $table->decimal('quantidade', 10, 3);
            $table->decimal('valor_por_unidade', 10, 3);
            $table->decimal('custo_total', 10, 2);
            $table->string('nome_posto')->nullable();
            $table->string('tipo_combustivel')->nullable();

            // Novos Campos de Nível do Tanque
            $table->string('nivel_tanque_chegada')->nullable()->comment('Nível do tanque na chegada ao posto.');
            $table->string('nivel_tanque_saida')->nullable()->comment('Nível do tanque após abastecer.');
            $table->boolean('tanque_cheio')->default(false)->comment('Flag para indicar se o tanque foi completado.');

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
