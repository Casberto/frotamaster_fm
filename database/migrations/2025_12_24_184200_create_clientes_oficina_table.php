<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes_oficina', function (Blueprint $table) {
            $table->id('clo_id');
            $table->unsignedBigInteger('clo_emp_id'); // Vínculo com a oficina
            $table->string('clo_nome');
            $table->string('clo_telefone'); // WhatsApp obrigatório
            $table->string('clo_documento')->nullable(); // CPF/CNPJ
            $table->boolean('clo_vip')->default(false); // Curva A
            $table->text('clo_obs')->nullable();
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('clo_emp_id')->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes_oficina');
    }
};
