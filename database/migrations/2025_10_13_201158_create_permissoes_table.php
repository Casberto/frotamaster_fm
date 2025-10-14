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
        Schema::create('permissoes', function (Blueprint $table) {
            $table->id('prm_id');
            $table->string('prm_modulo', 100);
            $table->string('prm_acao', 50);
            $table->string('prm_descricao', 255)->nullable();
            $table->timestamps();

            $table->unique(['prm_modulo', 'prm_acao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissoes');
    }
};
