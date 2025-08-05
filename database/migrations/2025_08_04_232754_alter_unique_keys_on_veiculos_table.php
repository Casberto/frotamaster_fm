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
        Schema::table('veiculos', function (Blueprint $table) {
            // 1. Remove a restrição unique individual antiga
            $table->dropUnique('veiculos_chassi_unique');
            $table->dropUnique('veiculos_renavam_unique');

            // 2. Adiciona a nova restrição unique composta
            $table->unique(['chassi', 'id_empresa']);
            $table->unique(['renavam', 'id_empresa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            // 1. Remove a restrição unique composta
            $table->dropUnique(['chassi', 'id_empresa']);
            $table->dropUnique(['renavam', 'id_empresa']);

            // 2. Recria a restrição unique individual original
            $table->unique('chassi', 'veiculos_chassi_unique');
            $table->unique('renavam', 'veiculos_renavam_unique');
        });
    }
};