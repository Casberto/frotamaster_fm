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
        Schema::table('veiculos_fotos', function (Blueprint $table) {
            if (!Schema::hasColumn('veiculos_fotos', 'vef_tipo')) {
                $table->string('vef_tipo')->default('Geral')->after('arquivo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('veiculos_fotos', function (Blueprint $table) {
             if (Schema::hasColumn('veiculos_fotos', 'vef_tipo')) {
                $table->dropColumn('vef_tipo');
             }
        });
    }
};
