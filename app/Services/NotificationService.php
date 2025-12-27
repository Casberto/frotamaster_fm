<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    /**
     * Retorna os usuários de uma empresa que possuem uma permissão específica.
     *
     * @param int $companyId O ID da empresa
     * @param string $permissionCode O código da permissão (ex: 'NOT001')
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecipients(int $companyId, string $permissionCode): Collection
    {
        return User::where('id_empresa', $companyId)
            ->whereHas('perfis', function ($query) use ($permissionCode) {
                $query->where('per_status', true)
                      ->whereHas('permissoes', function ($q) use ($permissionCode) {
                          $q->where('prm_codigo', $permissionCode);
                      });
            })
            ->get();
    }
}
