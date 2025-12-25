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
        // Altering ENUM column using raw SQL because Doctrine/DBAL has issues with ENUMs sometimes
        // and raw SQL is safer for this specific MySQL operation.
        DB::statement("ALTER TABLE ordens_servico MODIFY COLUMN osv_status ENUM('aguardando', 'diagnostico', 'aprovacao', 'aprovado', 'pecas', 'execucao', 'pronto', 'entregue', 'cancelado') DEFAULT 'aguardando'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Returning to original state
        DB::statement("ALTER TABLE ordens_servico MODIFY COLUMN osv_status ENUM('aguardando', 'diagnostico', 'aprovacao', 'aprovado', 'pecas', 'execucao', 'pronto', 'entregue') DEFAULT 'aguardando'");
    }
};
