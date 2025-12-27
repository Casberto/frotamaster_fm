<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\CompanyProfile;

class Empresa extends Model
{
    use HasFactory;



    protected $fillable = [
        'tipo',
        'profile',
        'modules',
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email_contato',
        'telefone_contato',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    protected $casts = [
        'profile' => CompanyProfile::class,
        'modules' => 'array',
    ];

    public static function getAllModules(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'reservas' => 'Reservas',
            'veiculos' => 'Veículos',
            'motoristas' => 'Motoristas',
            'manutencoes' => 'Manutenções',
            'abastecimentos' => 'Abastecimentos',
            'documentos' => 'Documentos',
            'seguros' => 'Seguros',
            'cadastros' => 'Cadastros (Fornecedores e Serviços)',
            'usuarios' => 'Usuários',
            'configuracoes' => 'Configurações',
            'oficina' => 'Oficina & Serviços',
        ];
    }

    public static function getDefaultModulesForProfile(string $profile): array
    {
        $allModules = array_keys(self::getAllModules());

        return match($profile) {
            'particular'          => ['dashboard', 'veiculos', 'manutencoes', 'abastecimentos', 'documentos', 'seguros', 'cadastros'],
            'frotista'            => array_merge($allModules, []), // Frotista has access to ALL, including motoristas
            'prestador_servico'   => array_values(array_filter($allModules, fn($m) => $m !== 'motoristas')), // Prestador defaults WITHOUT motoristas, but can add
            default               => ['dashboard', 'veiculos'],
        };
    }

    public function hasModule(string $module): bool
    {
        // Se a coluna modules ainda estiver vazia (legado ou erro), fallback para o profile
        if (empty($this->modules)) {
             $myProfile = $this->profile->value ?? 'frotista';
             return in_array($module, self::getDefaultModulesForProfile($myProfile));
        }

        return in_array($module, $this->modules);
    }

    public function licencas()
    {
        return $this->hasMany(Licenca::class, 'id_empresa');
    }

    public function activeLicense()
    {
        return $this->hasOne(Licenca::class, 'id_empresa')->where('status', 'ativo')->latest('data_vencimento');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_empresa');
    }

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'vei_emp_id');
    }

    /**
     * Conta o total de anexos (documentos) vinculados aos veículos da frota.
     * Como não há documentos diretos da empresa ainda, usamos essa métrica.
     */
    public function getTotalAnexosAttribute()
    {
        // Carrega a contagem de documentos para cada veículo e soma
        return $this->veiculos()->withCount('documentos')->get()->sum('documentos_count');
    }

    public function configuracoes()
    {
        return $this->hasMany(ConfiguracaoEmpresa::class, 'cfe_emp_id');
    }
}

