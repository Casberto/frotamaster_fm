<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veiculos_terceiros', function (Blueprint $table) {
            $table->id('vct_id');
            $table->unsignedBigInteger('vct_clo_id'); // Dono do carro
            $table->string('vct_placa', 10);
            $table->string('vct_marca');
            $table->string('vct_modelo');
            $table->integer('vct_ano')->nullable();
            $table->string('vct_cor')->nullable();
            $table->string('vct_combustivel')->nullable();
            $table->string('vct_chassi')->nullable(); // Opcional
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('vct_clo_id')->references('clo_id')->on('clientes_oficina');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos_terceiros');
    }
};
