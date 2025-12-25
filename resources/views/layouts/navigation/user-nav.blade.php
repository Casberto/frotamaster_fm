{{-- =============================================== --}}
{{-- MENU DO CLIENTE (USUÁRIO MASTER/COMUM) --}}
{{-- =============================================== --}}

@php
    $isPrestador = optional(auth()->user()->empresa)->profile?->value === 'prestador_servico';
@endphp

{{-- Módulo Oficina (Prioritário para Prestadores) --}}
@include('layouts.navigation.user.oficina', ['isPrestador' => $isPrestador])

{{-- Módulo Reservas (Bloqueado para Prestadores) --}}
@if(!$isPrestador && optional(auth()->user()->empresa)->hasModule('reservas'))
    @include('layouts.navigation.user.agendamentos')
@endif

{{-- Módulo Veículos (Bloqueado para Prestadores) --}}
@if(!$isPrestador && optional(auth()->user()->empresa)->hasModule('veiculos'))
    @include('layouts.navigation.user.frota')
@endif

{{-- Módulo Motoristas (Bloqueado para Prestadores) --}}
@if(!$isPrestador && optional(auth()->user()->empresa)->hasModule('motoristas'))
    @include('layouts.navigation.user.motoristas')
@endif

{{-- Módulo Manutenções (Bloqueado para Prestadores) --}}
@if(!$isPrestador && optional(auth()->user()->empresa)->hasModule('manutencoes'))
    @include('layouts.navigation.user.manutencoes')
@endif

{{-- Módulo Abastecimentos (Bloqueado para Prestadores) --}}
@if(!$isPrestador && optional(auth()->user()->empresa)->hasModule('abastecimentos'))
    @include('layouts.navigation.user.abastecimentos')
@endif

{{-- Módulo Documentos --}}
@if(optional(auth()->user()->empresa)->hasModule('documentos'))
    @include('layouts.navigation.user.documentos')
@endif

{{-- Módulo Seguros --}}
@if(optional(auth()->user()->empresa)->hasModule('seguros'))
    @include('layouts.navigation.user.seguros')
@endif


{{-- Módulo Cadastros --}}
@if(optional(auth()->user()->empresa)->hasModule('cadastros'))
    @include('layouts.navigation.user.cadastros')
@endif

{{-- Módulo Usuários --}}
@if(optional(auth()->user()->empresa)->hasModule('usuarios'))
    @include('layouts.navigation.user.usuarios')
@endif

{{-- Módulo Configurações --}}
@if(optional(auth()->user()->empresa)->hasModule('configuracoes'))
    @include('layouts.navigation.user.parametros')
@endif
