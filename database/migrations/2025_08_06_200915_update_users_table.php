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
        // Modifica a tabela 'users' para adicionar o campo 'must_change_password'
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a nova coluna 'must_change_password' como booleana, com valor padrão false
            // e posiciona-a após a coluna 'role'
            $table->boolean('must_change_password')->default(false)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte as modificações na tabela 'users'
        Schema::table('users', function (Blueprint $table) {
            // Remove a coluna 'must_change_password' se a migração for revertida
            $table->dropColumn('must_change_password');
        });
    }
};

