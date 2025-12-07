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
        Schema::table('abastecimentos', function (Blueprint $table) {
            $table->boolean('aba_aditivado')->default(false)->after('aba_combustivel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abastecimentos', function (Blueprint $table) {
            //
        });
    }
};
