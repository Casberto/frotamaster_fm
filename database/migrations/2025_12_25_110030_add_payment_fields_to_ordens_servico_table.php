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
        Schema::table('ordens_servico', function (Blueprint $table) {
            $table->enum('osv_status_pagamento', ['pendente', 'pago'])->default('pendente')->after('osv_valor_custo_total');
            $table->enum('osv_forma_pagamento', ['pix', 'dinheiro', 'cartao_credito', 'cartao_debito', 'boleto'])->nullable()->after('osv_status_pagamento');
            $table->date('osv_data_pagamento')->nullable()->after('osv_forma_pagamento');
            $table->date('osv_data_compensacao')->nullable()->after('osv_data_pagamento'); // Quando o dinheiro cai (D+30 etc)
        });
    }

    public function down(): void
    {
        Schema::table('ordens_servico', function (Blueprint $table) {
            $table->dropColumn([
                'osv_status_pagamento',
                'osv_forma_pagamento',
                'osv_data_pagamento',
                'osv_data_compensacao'
            ]);
        });
    }
};
