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
        Schema::create('perfis', function (Blueprint $table) {
            $table->id('per_id');
            $table->foreignId('per_emp_id')->constrained('empresas', 'id')->onDelete('cascade');
            $table->string('per_nome', 100);
            $table->string('per_descricao', 255)->nullable();
            $table->boolean('per_status')->default(true);
            $table->timestamps();

            $table->unique(['per_nome', 'per_emp_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfis');
    }
};
