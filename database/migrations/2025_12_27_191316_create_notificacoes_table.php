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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->uuid('not_id')->primary();
            $table->string('not_type');
            $table->string('not_notifiable_type');
            $table->unsignedBigInteger('not_notifiable_id');
            $table->text('not_data');
            $table->timestamp('not_read_at')->nullable();
            
            // Custom timestamps
            $table->timestamp('not_created_at')->useCurrent();
            $table->timestamp('not_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['not_notifiable_type', 'not_notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
