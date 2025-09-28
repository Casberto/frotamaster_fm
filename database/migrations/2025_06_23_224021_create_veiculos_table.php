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
            // Bloco 1: Identificação e Relacionamentos Essenciais
            $table->bigIncrements('vei_id');
            $table->unsignedBigInteger('vei_emp_id')->comment('Chave estrangeira para a tabela de empresas (empresas.id).');
            $table->unsignedBigInteger('vei_user_id')->comment('Chave estrangeira para o usuário que cadastrou (users.id).');
            $table->tinyInteger('vei_segmento')->comment('Segmento da empresa: 1-Particular, 2-Frotista, 3-Revendedor, 4-Transportadora.');

            // Bloco 2: Dados de Identificação Única do Veículo (Obrigatórios)
            $table->string('vei_placa', 8)->comment('Placa do veículo (padrão Mercosul/antigo).');
            $table->string('vei_chassi', 17)->comment('Número do Chassi (VIN).');
            $table->string('vei_renavam', 11)->comment('Registro Nacional de Veículos Automotores.');
            $table->year('vei_ano_fab')->comment('Ano de Fabricação.');
            $table->year('vei_ano_mod')->comment('Ano do Modelo.');
            $table->string('vei_fabricante', 50)->comment('Nome do Fabricante/Montadora.');
            $table->string('vei_modelo', 50)->comment('Nome do Modelo do veículo.');

            // Bloco 3: Classificação e Características (Padrão CONTRAN)
            $table->unsignedTinyInteger('vei_tipo')->comment('Código do Tipo do Veículo, baseado na Tabela do Anexo I da Resolução 916/2022.');
            $table->unsignedTinyInteger('vei_especie')->comment('Código da Espécie, baseado na Tabela do Anexo I.');
            $table->unsignedSmallInteger('vei_carroceria')->comment('Código da Carroceria, baseado na Tabela do Anexo I.');
            $table->unsignedTinyInteger('vei_combustivel')->comment('Código do tipo de combustível.');
            $table->string('vei_cor_predominante', 30)->comment('Cor predominante do veículo.');

            // Bloco 4: Dados Operacionais e de Motor
            $table->string('vei_potencia', 10)->nullable()->comment('Potência em CV ou KW.');
            $table->string('vei_cilindradas', 10)->nullable()->comment('Cilindradas em CC ou L.');
            $table->string('vei_num_motor', 30)->nullable()->comment('Número de identificação do motor.');
            $table->decimal('vei_cap_tanque', 7, 2)->nullable()->comment('Capacidade do tanque (Litros) ou bateria (kWh).');
            $table->unsignedInteger('vei_km_inicial')->default(0)->comment('Quilometragem no momento da aquisição/cadastro.');
            $table->unsignedInteger('vei_km_atual')->default(0)->comment('Última quilometragem registrada do veículo.');

            // Bloco 5: Dados de Documentação e Controle (Segmento Avançado/Premium)
            $table->string('vei_crv', 12)->nullable()->comment('Código de Registro do Veículo (antigo DUT).');
            $table->date('vei_data_licenciamento')->nullable()->comment('Data do último licenciamento pago.');
            $table->date('vei_venc_licenciamento')->nullable()->comment('Data de vencimento do próximo licenciamento.');
            $table->string('vei_antt', 20)->nullable()->comment('Registro na ANTT (para transportadoras).');
            $table->unsignedInteger('vei_tara')->nullable()->comment('Peso do veículo sem carga (em kg).');
            $table->unsignedInteger('vei_lotacao')->nullable()->comment('Capacidade de carga útil (em kg).');
            $table->unsignedInteger('vei_pbt')->nullable()->comment('Peso Bruto Total (Tara + Lotação em kg).');

            // Bloco 6: Dados de Gestão e Histórico
            $table->date('vei_data_aquisicao');
            $table->decimal('vei_valor_aquisicao', 10, 2)->nullable();
            $table->date('vei_data_venda')->nullable();
            $table->decimal('vei_valor_venda', 10, 2)->nullable();
            $table->tinyInteger('vei_status')->default(1)->comment('1-Ativo, 2-Inativo, 3-Em Manutenção, 4-Vendido.');
            $table->mediumText('vei_obs')->nullable();

            // Bloco 7: Timestamps
            $table->timestamps();

            // Bloco 8: Índices e Chaves Estrangeiras
            $table->index('vei_emp_id', 'idx_vei_emp_id');
            $table->index('vei_placa', 'idx_vei_placa');
            $table->index('vei_status', 'idx_vei_status');

            $table->unique(['vei_placa', 'vei_emp_id'], 'uk_veiculos_placa_empresa');
            $table->unique(['vei_chassi', 'vei_emp_id'], 'uk_veiculos_chassi_empresa');
            $table->unique(['vei_renavam', 'vei_emp_id'], 'uk_veiculos_renavam_empresa');

            $table->foreign('vei_emp_id', 'fk_veiculos_empresa')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('vei_user_id', 'fk_veiculos_user')->references('id')->on('users')->onDelete('restrict');
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
