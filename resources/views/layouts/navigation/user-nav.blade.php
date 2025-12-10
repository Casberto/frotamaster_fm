{{-- =============================================== --}}
{{-- MENU DO CLIENTE (USUÁRIO MASTER/COMUM) --}}
{{-- =============================================== --}}

{{-- Módulo Reservas (Agendamentos) --}}
{{-- Módulo Reservas (Agendamentos) --}}
@if(optional(auth()->user()->empresa)->tipo !== 'PF')
    @include('layouts.navigation.user.agendamentos')
@endif

{{-- Módulo Veículos (Frota) --}}
@include('layouts.navigation.user.frota')

{{-- Módulo Motoristas --}}
@include('layouts.navigation.user.motoristas')

{{-- Módulo Manutenções --}}
@include('layouts.navigation.user.manutencoes')

{{-- Módulo Abastecimentos --}}
@include('layouts.navigation.user.abastecimentos')

{{-- Módulo Documentos --}}
@include('layouts.navigation.user.documentos')

{{-- Módulo Seguros --}}
@include('layouts.navigation.user.seguros')


{{-- Módulo Cadastros --}}
@include('layouts.navigation.user.cadastros')

{{-- Módulo Usuários --}}
{{-- Módulo Usuários --}}
@if(optional(auth()->user()->empresa)->tipo !== 'PF')
    @include('layouts.navigation.user.usuarios')
@endif

{{-- Módulo Configurações --}}
@include('layouts.navigation.user.parametros')
