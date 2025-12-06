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
        Schema::create('veiculos_fotos', function (Blueprint $table) {
            $table->id('vef_id');
            $table->unsignedBigInteger('vef_vei_id');
            $table->string('arquivo');
            $table->string('vef_tipo')->default('Geral');
            $table->timestamp('vef_criado_em')->useCurrent();
            
            $table->foreign('vef_vei_id')->references('vei_id')->on('veiculos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos_fotos');
    }
};
