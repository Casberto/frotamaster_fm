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
        Schema::table('empresas', function (Blueprint $table) {
            $table->json('modules')->nullable()->after('profile');
        });

        // Migration Logic to populate modules based on existing profile
        $empresas = \Illuminate\Support\Facades\DB::table('empresas')->get();

        foreach ($empresas as $empresa) {
            $modules = match($empresa->profile) {
                'transportadora' => ['dashboard', 'veiculos', 'manutencao', 'abastecimento', 'motoristas', 'viagens', 'cargas', 'seguros', 'cadastros', 'usuarios', 'financeiro'],
                'frotista'       => ['dashboard', 'veiculos', 'manutencao', 'abastecimento', 'agendamentos', 'motoristas', 'seguros', 'cadastros', 'usuarios', 'financeiro'],
                'revenda'        => ['dashboard', 'veiculos', 'cadastros', 'financeiro', 'documentacao'],
                'particular'     => ['dashboard', 'veiculos', 'manutencao', 'abastecimento', 'cadastros', 'seguros', 'usuarios'], // Added usuarios to manage own profile
                default          => ['dashboard', 'veiculos'],
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
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('modules');
        });
    }
};
