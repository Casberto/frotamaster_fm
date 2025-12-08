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
        // if (!Schema::hasTable('seguros_sinistros_fotos')) {
        //     Schema::create('seguros_sinistros_fotos', function (Blueprint $table) {
        //         $table->id('ssf_id');
        //         $table->unsignedBigInteger('ssf_ssi_id');
        //         $table->string('arquivo');
        //         $table->string('ssf_tipo')->default('Geral');
        //         $table->timestamp('ssf_criado_em')->useCurrent();
        //         
        //         $table->foreign('ssf_ssi_id')->references('ssi_id')->on('seguros_sinistro')->onDelete('cascade');
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguros_sinistros_fotos');
    }
};
