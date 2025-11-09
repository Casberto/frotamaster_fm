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
        Schema::create('reservas_audit_logs', function (Blueprint $table) {
            $table->bigIncrements('ral_id');
            
            $table->foreignId('ral_res_id')->comment('ID da Reserva (FK de reservas)')->constrained('reservas', 'res_id')->onDelete('cascade');
            $table->foreignId('ral_user_id')->nullable()->comment('ID do Usuário (FK de users) que fez a ação')->constrained('users', 'id')->onDelete('set null');
            
            $table->string('ral_acao', 100)->comment('Ação realizada (ex: create, update, approve, finish)');
            $table->json('ral_before_json')->nullable()->comment('Estado anterior (JSON)');
            $table->json('ral_after_json')->nullable()->comment('Estado posterior (JSON)');
            
            $table->timestamp('created_at')->useCurrent();

            $table->index('ral_res_id');
            $table->index('ral_user_id');
            $table->index('ral_acao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas_audit_logs');
    }
};
