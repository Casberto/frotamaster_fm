<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Limpar a tabela antes de adicionar a coluna única
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissoes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('permissoes', function (Blueprint $table) {
           if (!Schema::hasColumn('permissoes', 'prm_codigo')) {
                $table->string('prm_codigo', 20)->unique()->after('prm_id');
           }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissoes', function (Blueprint $table) {
            if (Schema::hasColumn('permissoes', 'prm_codigo')) {
                $table->dropColumn('prm_codigo');
            }
        });
    }
};
