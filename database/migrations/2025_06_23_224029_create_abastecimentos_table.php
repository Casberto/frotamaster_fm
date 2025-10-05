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
        Schema::dropIfExists('abastecimentos');
        
        Schema::create('abastecimentos', function (Blueprint $table) {
            // Chaves
            $table->id('aba_id');
            $table->foreignId('aba_emp_id')->constrained('empresas', 'id')->onDelete('cascade');
            $table->foreignId('aba_user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('aba_vei_id')->constrained('veiculos', 'vei_id')->onDelete('cascade');
            $table->foreignId('aba_for_id')->nullable()->constrained('fornecedores', 'for_id')->onDelete('set null')->comment('FK para o posto de combustível (fornecedor)');

            // Dados Essenciais
            $table->date('aba_data')->comment('Data do abastecimento');
            $table->unsignedInteger('aba_km')->comment('Quilometragem do veículo no momento do abastecimento');

            // Valores
            $table->enum('aba_und_med', ['L', 'm³', 'kWh'])->comment('Unidade de Medida: Litros, Metros Cúbicos para GNV, kWh para elétricos');
            $table->decimal('aba_qtd', 10, 3)->comment('Quantidade abastecida/carregada');
            $table->decimal('aba_vlr_und', 10, 3)->comment('Valor por unidade (R$ por Litro, m³, kWh)');
            $table->decimal('aba_vlr_tot', 10, 2)->comment('Valor total pago');

            // Detalhes do Abastecimento
            $table->tinyInteger('aba_combustivel')->nullable()->comment('Tipo de combustível usado (para veículos flex/híbridos). 1: Gasolina, 2: Etanol, etc.');
            $table->boolean('aba_tanque_cheio')->default(false)->comment('Indica se o tanque foi completado');
            $table->enum('aba_tanque_inicio', ['reserva', '25', '50', '75', '100'])->nullable()->comment('Nível do tanque na chegada (percentual aproximado)');

            // Checklist Rápido (Opcional)
            $table->boolean('aba_pneus_calibrados')->default(false);
            $table->boolean('aba_agua_verificada')->default(false);
            $table->boolean('aba_oleo_verificado')->default(false);

            // Observações
            $table->text('aba_obs')->nullable();

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
