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
        Schema::create('os_historico', function (Blueprint $table) {
            $table->id('osh_id');
            $table->foreignId('osh_osv_id')->constrained('ordens_servico', 'osv_id')->onDelete('cascade');
            $table->foreignId('osh_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('osh_acao'); // Ex: 'Criou OS', 'Adicionou Item', 'Aprovou'
            $table->text('osh_descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('os_historico');
    }
};
