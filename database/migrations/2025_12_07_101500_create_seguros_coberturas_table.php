<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguros_cobertura', function (Blueprint $table) {
            $table->id('sco_id');
            $table->unsignedBigInteger('sco_seg_id')->nullable();
            $table->string('sco_titulo')->nullable();
            $table->text('sco_descricao')->nullable();
            $table->decimal('sco_valor', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('sco_seg_id')->references('seg_id')->on('seguros_apolice')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguros_cobertura');
    }
};
