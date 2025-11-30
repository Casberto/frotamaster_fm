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
            $table->string('vei_chassi', 17)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            // We need to be careful here. If there are null values, this might fail.
            // But for a down migration, we assume we want to go back to the previous state.
            // Ideally we would fill nulls or delete them, but strictly speaking we just revert the schema.
            $table->string('vei_chassi', 17)->nullable(false)->change();
        });
    }
};
