<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguros_apolice', function (Blueprint $table) {
            $table->id('seg_id');
            $table->unsignedBigInteger('seg_emp_id')->nullable(); // Empresa
            $table->unsignedBigInteger('seg_vei_id')->nullable(); // Veículo
            $table->unsignedBigInteger('seg_for_id')->nullable(); // Fornecedor (Seguradora)
            $table->string('seg_numero')->nullable();
            $table->date('seg_inicio')->nullable();
            $table->date('seg_fim')->nullable();
            $table->decimal('seg_valor_total', 10, 2)->nullable();
            $table->integer('seg_parcelas')->nullable();
            $table->string('seg_tipo')->nullable(); // Casco, RCF, etc
            $table->decimal('seg_franquia', 10, 2)->nullable();
            $table->text('seg_obs')->nullable();
            $table->string('seg_status')->nullable(); // Ativo, Vencido, Em renovação
            
            // FKs (if logical deletes or optional)
            // $table->foreign('seg_emp_id')->references('emp_id')->on('empresas'); 
            // $table->foreign('seg_vei_id')->references('vei_id')->on('veiculos');
            // $table->foreign('seg_for_id')->references('for_id')->on('fornecedores');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguros_apolice');
    }
};
