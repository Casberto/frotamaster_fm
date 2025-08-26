<?php
// database/migrations/2025_08_06_200002_create_licencas_table.php (MODIFICADO)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            // $table->foreignId('id_plano')->constrained('planos'); // REMOVIDO
            $table->string('plano'); // ADICIONADO (Ex: Mensal, Trimestral, etc.)
            $table->foreignId('id_usuario_criador')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('valor_pago', 10, 2)->default(0.00);
            $table->date('data_inicio');
            $table->date('data_vencimento');
            $table->enum('status', ['ativo', 'expirado', 'pendente', 'cancelado'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licencas');
    }
};