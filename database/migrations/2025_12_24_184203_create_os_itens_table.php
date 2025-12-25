<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('os_itens', function (Blueprint $table) {
            $table->id('osi_id');
            $table->unsignedBigInteger('osi_osv_id');
            
            $table->enum('osi_tipo', ['peca', 'servico']);
            $table->string('osi_descricao'); // Ex: "Filtro de Óleo" ou "Mão de Obra Troca"
            $table->integer('osi_quantidade')->default(1);
            
            $table->decimal('osi_valor_custo_unit', 10, 2)->default(0); // Quanto a oficina pagou
            $table->decimal('osi_valor_venda_unit', 10, 2)->default(0); // Quanto cobrou do cliente
            
            $table->boolean('osi_aprovado')->default(true); // Cliente pode reprovar item específico? (Futuro)
            
            $table->timestamps();
            $table->foreign('osi_osv_id')->references('osv_id')->on('ordens_servico');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('os_itens');
    }
};
