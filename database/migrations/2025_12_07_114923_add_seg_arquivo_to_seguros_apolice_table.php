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
        Schema::table('seguros_apolice', function (Blueprint $table) {
            $table->string('seg_arquivo')->nullable()->after('seg_obs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seguros_apolice', function (Blueprint $table) {
            $table->dropColumn('seg_arquivo');
        });
    }
};
