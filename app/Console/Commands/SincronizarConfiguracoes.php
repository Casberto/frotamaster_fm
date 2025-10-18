<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\ConfiguracaoPadrao;
use App\Models\ConfiguracaoEmpresa;
use Illuminate\Support\Facades\DB;

class SincronizarConfiguracoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza as configurações padrão com todas as empresas cadastradas, adicionando apenas as que faltam.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando a sincronização de configurações...');

        try {
            // Busca todas as configurações padrão e todas as empresas
            $configuracoesPadrao = ConfiguracaoPadrao::all();
            $empresas = Empresa::all();

            if ($empresas->isEmpty()) {
                $this->warn('Nenhuma empresa encontrada. Nenhuma ação necessária.');
                return;
            }

            if ($configuracoesPadrao->isEmpty()) {
                $this->warn('Nenhuma configuração padrão encontrada. Nenhuma ação necessária.');
                return;
            }

            $totalAdicionadas = 0;

            // Itera sobre cada empresa para verificar e adicionar configurações faltantes
            foreach ($empresas as $empresa) {
                $this->line("Verificando empresa: {$empresa->nome_fantasia} (ID: {$empresa->id})");

                // Busca as configurações que esta empresa já possui
                $configsExistentes = ConfiguracaoEmpresa::where('cfe_emp_id', $empresa->id)
                                                        ->pluck('cfe_cfp_id')
                                                        ->all();

                foreach ($configuracoesPadrao as $configPadrao) {
                    // Se a empresa ainda não possui esta configuração, adiciona
                    if (!in_array($configPadrao->cfp_id, $configsExistentes)) {
                        ConfiguracaoEmpresa::create([
                            'cfe_emp_id' => $empresa->id,
                            'cfe_cfp_id' => $configPadrao->cfp_id,
                            'cfe_valor'  => $configPadrao->cfp_valor, // Usa o valor padrão
                        ]);
                        $this->comment(" -> Adicionada configuração: '{$configPadrao->cfp_chave}'");
                        $totalAdicionadas++;
                    }
                }
            }

            $this->info("\nSincronização concluída com sucesso!");
            $this->info("Total de novas configurações adicionadas: {$totalAdicionadas}");

        } catch (\Exception $e) {
            $this->error('Ocorreu um erro durante a sincronização:');
            $this->error($e->getMessage());
        }
    }
}
