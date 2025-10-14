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
        Schema::create('usuario_perfis', function (Blueprint $table) {
            $table->id('usp_id');
            $table->foreignId('usp_emp_id')->constrained('empresas', 'id')->onDelete('cascade');
            $table->foreignId('usp_usr_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('usp_per_id')->constrained('perfis', 'per_id')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['usp_usr_id', 'usp_per_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_perfis');
    }
};
