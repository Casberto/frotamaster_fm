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
        // 1. Atualizar Perfis Antigos para 'Prestador de Serviço'
        \Illuminate\Support\Facades\DB::table('empresas')
            ->whereIn('profile', ['transportadora', 'revenda'])
            ->update(['profile' => 'prestador_servico']);

        // 2. Re-popular a coluna 'modules' para TODAS as empresas com base no novo padrão
        $empresas = \Illuminate\Support\Facades\DB::table('empresas')->get();

        foreach ($empresas as $empresa) {
            $modules = match($empresa->profile) {
                'particular'        => ['dashboard', 'veiculos', 'manutencoes', 'abastecimentos', 'documentos', 'seguros', 'cadastros'],
                'frotista'          => ['dashboard', 'reservas', 'veiculos', 'manutencoes', 'abastecimentos', 'documentos', 'seguros', 'cadastros', 'usuarios', 'configuracoes'],
                'prestador_servico' => ['dashboard', 'reservas', 'veiculos', 'manutencoes', 'abastecimentos', 'documentos', 'seguros', 'cadastros', 'usuarios', 'configuracoes'],
                default             => ['dashboard', 'veiculos'],
            };

            \Illuminate\Support\Facades\DB::table('empresas')
                ->where('id', $empresa->id)
                ->update(['modules' => json_encode($modules)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter é complexo pois dados foram fundidos, mantemos lógica vazia ou parcial
        // Se necessário reverter, teria que ser manual ou restaurando backup
    }
};
