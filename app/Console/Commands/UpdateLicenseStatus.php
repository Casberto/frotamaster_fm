<?php
// app/Console/Commands/UpdateLicenseStatus.php (NOVO)

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Licenca;
use Carbon\Carbon;

class UpdateLicenseStatus extends Command
{
    protected $signature = 'licencas:update-status';
    protected $description = 'Verifica e atualiza o status de licenças ativas que expiraram';

    public function handle()
    {
        $this->info('Verificando licenças expiradas...');

        $licencasExpiradas = Licenca::where('status', 'ativo')
                                    ->where('data_vencimento', '<', Carbon::today())
                                    ->get();

        if ($licencasExpiradas->isEmpty()) {
            $this->info('Nenhuma licença expirada encontrada.');
            return;
        }

        foreach ($licencasExpiradas as $licenca) {
            $licenca->status = 'expirado';
            $licenca->save();
            $this->line("Licença ID {$licenca->id} da empresa '{$licenca->empresa->nome_fantasia}' atualizada para 'expirado'.");
        }

        $this->info('Verificação concluída. ' . $licencasExpiradas->count() . ' licença(s) atualizada(s).');
    }
}


