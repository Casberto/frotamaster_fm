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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->nullable()->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_name');
            $table->string('tela'); // Ex: 'Veículos', 'Manutenções'
            $table->string('acao'); // Ex: 'create', 'update', 'delete'
            $table->unsignedBigInteger('registro_id'); // ID do registro afetado (ex: id do veículo)
            $table->string('registro_string'); // Uma representação em texto do registro (ex: placa do veículo)
            $table->text('dados_antigos')->nullable(); // JSON com os dados antes da alteração
            $table->text('dados_novos')->nullable(); // JSON com os dados depois da alteração
            $table->timestamps(); // data e hora do log
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
