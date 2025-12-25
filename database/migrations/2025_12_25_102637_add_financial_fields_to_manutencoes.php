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
        Schema::table('manutencoes', function (Blueprint $table) {
            // CUSTOS (Saídas)
            // Ajustado 'after' para man_custo_total pois 'descricao' não existe (existe man_observacoes ou man_custo_total)
            $table->decimal('man_val_pecas', 10, 2)->default(0)->after('man_custo_total')
                  ->comment('Custo interno de peças');
            $table->decimal('man_val_mao_obra', 10, 2)->default(0)->after('man_val_pecas')
                  ->comment('Custo interno de mão de obra');

            // RECEITAS (Entradas)
            $table->decimal('man_val_cobrado', 10, 2)->default(0)->after('man_val_mao_obra')
                  ->comment('Valor total cobrado ao cliente (Faturamento)');

            // FLUXO DE CAIXA
            // Ajustado 'after' para man_status
            $table->enum('man_status_pagamento', ['pendente', 'pago', 'atrasado', 'cancelado'])
                  ->default('pendente')->after('man_status');
                  
            $table->string('man_forma_pagamento')->nullable()->after('man_status_pagamento');
            
            // DATAS CRÍTICAS
            $table->date('man_dat_vencimento')->nullable()->after('man_forma_pagamento')->comment('Data limite para pagamento');
            $table->date('man_dat_pagamento')->nullable()->after('man_dat_vencimento')->comment('Data da baixa do cliente');
            $table->date('man_dat_compensacao')->nullable()->after('man_dat_pagamento')->comment('Data da liquidez real (D+30, etc)');
        });
    }

    public function down(): void
    {
        Schema::table('manutencoes', function (Blueprint $table) {
            $table->dropColumn([
                'man_val_pecas',
                'man_val_mao_obra',
                'man_val_cobrado',
                'man_status_pagamento',
                'man_forma_pagamento',
                'man_dat_vencimento',
                'man_dat_pagamento',
                'man_dat_compensacao'
            ]);
        });
    }
};
