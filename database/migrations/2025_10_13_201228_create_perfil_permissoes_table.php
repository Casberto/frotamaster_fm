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
        Schema::create('perfil_permissoes', function (Blueprint $table) {
            $table->id('ppr_id');
            $table->foreignId('ppr_emp_id')->constrained('empresas', 'id')->onDelete('cascade');
            $table->foreignId('ppr_per_id')->constrained('perfis', 'per_id')->onDelete('cascade');
            $table->foreignId('ppr_prm_id')->constrained('permissoes', 'prm_id')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['ppr_per_id', 'ppr_prm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_permissoes');
    }
};
