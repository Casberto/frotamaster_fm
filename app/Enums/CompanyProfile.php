<?php

namespace App\Enums;

enum CompanyProfile: string
{
    case PARTICULAR = 'particular';
    case FROTISTA = 'frotista';
    case PRESTADOR_SERVICO = 'prestador_servico';

    public function label(): string
    {
        return match($this) {
            self::PARTICULAR => 'Uso Particular',
            self::FROTISTA => 'Frotista',
            self::PRESTADOR_SERVICO => 'Prestação de Serviço',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::PARTICULAR => 'Gestão simplificada: Veículos, Manutenções, Abastecimentos, Documentos, Seguros e Cadastros.',
            self::FROTISTA => 'Gestão completa para empresas com frota própria. Acesso a todos os módulos.',
            self::PRESTADOR_SERVICO => 'Foco em prestação de serviços e transporte. Acesso a todos os módulos.',
        };
    }
}
