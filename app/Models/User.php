<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        return $this->morphMany(Notificacao::class, 'not_notifiable')
                    ->orderBy('not_created_at', 'desc');
    }

    /**
     * Get the entity's read notifications.
     */
    public function readNotifications()
    {
        return $this->notifications()->whereNotNull('not_read_at');
    }

    /**
     * Get the entity's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('not_read_at');
    }

    /**
     * Cache local de permissões para a requisição atual.
     */
    protected array $permissionCache = [];

    protected $fillable = [
        'id_empresa',
        'name',
        'email',
        'password',
        'role',
        'must_change_password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'profile_photo_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? route('user.profile-photo', ['filename' => basename($this->profile_photo_path)])
                    : asset('img/default-avatar.svg'); // Imagem padrao SVG
    }

    // --- RELACIONAMENTOS ---

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function licencasCriadas()
    {
        return $this->hasMany(Licenca::class, 'id_usuario_criador');
    }

    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'usuario_perfis', 'usp_usr_id', 'usp_per_id');
    }

    public function motorista(): HasOne
    {
        return $this->hasOne(Motorista::class, 'mot_user_id', 'id');
    }

    public function reservasSolicitadas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'res_sol_id', 'id');
    }

    public function reservasAuditLogs(): HasMany
    {
        return $this->hasMany(ReservaAuditLog::class, 'ral_user_id', 'id');
    }

    // --- MÉTODOS AUXILIARES ---

    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    public function isMaster()
    {
        return $this->role === 'master';
    }

    /**
     * Verifica se o usuário tem uma permissão específica pelo CÓDIGO (ID).
     * Este é o método principal para controle de acesso granular.
     *
     * @param int $prmId O ID da permissão (tabela permissoes)
     * @return bool
     */
    public function temPermissaoId(int $prmId): bool
    {
        // Super-admin e Master têm acesso total na sua empresa
        if ($this->isSuperAdmin() || $this->isMaster()) {
            return true;
        }

        $key = "perm_id_{$prmId}";

        if (isset($this->permissionCache[$key])) {
            return $this->permissionCache[$key];
        }

        // Verifica nos perfis ativos se existe a permissão com o ID informado
        $tem = $this->perfis()
            ->where('per_status', true)
            ->whereHas('permissoes', function ($query) use ($prmId) {
                $query->where('permissoes.prm_id', $prmId);
            })
            ->exists();

        return $this->permissionCache[$key] = $tem;
    }

    /**
     * Verifica se o usuário tem uma permissão específica pelo CÓDIGO (String).
     *
     * @param string $codigo O Código da permissão (ex: 'VEI001')
     * @return bool
     */
    public function temPermissao(string $codigo): bool
    {
        // Super-admin e Master têm acesso total na sua empresa
        if ($this->isSuperAdmin() || $this->isMaster()) {
            return true;
        }

        $key = "perm_code_{$codigo}";

        if (isset($this->permissionCache[$key])) {
            return $this->permissionCache[$key];
        }

        // Verifica nos perfis ativos se existe a permissão com o Código informado
        $tem = $this->perfis()
            ->where('per_status', true)
            ->whereHas('permissoes', function ($query) use ($codigo) {
                $query->where('prm_codigo', $codigo);
            })
            ->exists();

        return $this->permissionCache[$key] = $tem;
    }

    /**
     * Mantido para compatibilidade com blade legados, mas recomenda-se usar o ID.
     */
    public function hasPermission(string $modulo, string $acao): bool
    {
        if ($this->isSuperAdmin() || $this->isMaster()) {
            return true;
        }
        
        $key = "{$modulo}_{$acao}";
        if (isset($this->permissionCache[$key])) {
            return $this->permissionCache[$key];
        }

        $tem = $this->perfis()
            ->where('per_status', true)
            ->whereHas('permissoes', function ($query) use ($modulo, $acao) {
                $query->where('prm_modulo', $modulo)
                      ->where('prm_acao', $acao);
            })
            ->exists();

        return $this->permissionCache[$key] = $tem;
    }
}