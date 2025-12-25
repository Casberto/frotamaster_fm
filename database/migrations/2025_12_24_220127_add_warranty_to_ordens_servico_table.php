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
            $table->integer('osv_dias_garantia')->nullable()->after('osv_data_saida');
            $table->date('osv_vencimento_garantia')->nullable()->after('osv_dias_garantia');
            $table->foreignId('osv_pai_id')->nullable()->after('osv_id')->constrained('ordens_servico', 'osv_id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordens_servico', function (Blueprint $table) {
            //
        });
    }
};
