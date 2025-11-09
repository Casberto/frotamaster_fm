<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_empresa',
        'name',
        'email',
        'password',
        'role', // Adicionado para o controle de perfil (super-admin, master, etc.)
        'must_change_password', // Novo campo para forçar a troca de senha no primeiro login
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define o relacionamento com o modelo Empresa.
     * Um Usuário pertence a uma Empresa (ou é super-admin sem empresa).
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    /**
     * Verifica se o usuário é um super-admin.
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    /**
     * Verifica se o usuário é um master.
     */
    public function isMaster()
    {
        return $this->role === 'master';
    }

    /**
     * Define o relacionamento com o modelo Licenca (licenças criadas por este usuário).
     * Um Usuário pode ter criado muitas Licenças.
     */
    public function licencasCriadas()
    {
        return $this->hasMany(Licenca::class, 'id_usuario_criador');
    }

    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'usuario_perfis', 'usp_usr_id', 'usp_per_id');
    }

    public function motorista(): HasOne
    {
        return $this->hasOne(Motorista::class, 'mot_user_id', 'id');
    }

    /**
     * Define o relacionamento com as Reservas que o usuário solicitou.
     */
    public function reservasSolicitadas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'res_sol_id', 'id');
    }

    /**
     * Define o relacionamento com os Logs de Auditoria de Reserva que o usuário gerou.
     */
    public function reservasAuditLogs(): HasMany
    {
        return $this->hasMany(ReservaAuditLog::class, 'ral_user_id', 'id');
    }
}
