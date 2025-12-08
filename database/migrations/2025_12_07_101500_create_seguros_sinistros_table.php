<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguros_sinistro', function (Blueprint $table) {
            $table->id('ssi_id');
            $table->unsignedBigInteger('ssi_seg_id')->nullable();
            $table->date('ssi_data')->nullable();
            $table->string('ssi_tipo')->nullable(); // Colisão, Furto, etc
            $table->decimal('ssi_valor_prejuizo', 10, 2)->nullable();
            $table->decimal('ssi_valor_coberto', 10, 2)->nullable();
            $table->string('ssi_status')->nullable(); // Em análise, Deferido, Indeferido
            $table->text('ssi_obs')->nullable();
            $table->text('ssi_anexos')->nullable(); // JSON list of files
            $table->timestamps();

            $table->foreign('ssi_seg_id')->references('seg_id')->on('seguros_apolice')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguros_sinistro');
    }
};
