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
        Schema::create('vei_documentos', function (Blueprint $table) {
            $table->bigIncrements('doc_id');
            $table->unsignedBigInteger('doc_vei_id');
            $table->unsignedBigInteger('doc_emp_id');
            $table->tinyInteger('doc_tipo')->comment('1-CRV, 2-CRLV, 3-Apólice Seguro, 4-Manual, 5-Outro');
            $table->string('doc_descricao', 100);
            $table->string('doc_path_arquivo', 255)->comment('Caminho para o arquivo armazenado.');
            $table->date('doc_data_emissao')->nullable();
            $table->date('doc_data_validade')->nullable();
            $table->timestamps();

            $table->index('doc_vei_id', 'idx_doc_vei_id');
            $table->index('doc_emp_id', 'idx_doc_emp_id');

            $table->foreign('doc_vei_id', 'fk_documentos_veiculo')->references('vei_id')->on('veiculos')->onDelete('cascade');
            $table->foreign('doc_emp_id', 'fk_documentos_empresa')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vei_documentos');
    }
};
