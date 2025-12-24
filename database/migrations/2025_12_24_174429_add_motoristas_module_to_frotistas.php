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
        // Pega todos os Frotistas ou Prestadores (se necessário) para garantir que tenham o módulo motoristas
        $empresas = \Illuminate\Support\Facades\DB::table('empresas')
            ->whereIn('profile', ['frotista']) 
            ->get();

        foreach ($empresas as $empresa) {
            $currentModules = json_decode($empresa->modules ?? '[]', true);
            
            if (!is_array($currentModules)) {
                $currentModules = [];
            }

            // Se não tiver motoristas, adiciona
            if (!in_array('motoristas', $currentModules)) {
                $currentModules[] = 'motoristas';
                
                \Illuminate\Support\Facades\DB::table('empresas')
                    ->where('id', $empresa->id)
                    ->update(['modules' => json_encode($currentModules)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
