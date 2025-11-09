{{-- =============================================== --}}
{{-- MENU DO CLIENTE (USUÁRIO MASTER/COMUM) --}}
{{-- =============================================== --}}

{{-- Módulo Frota --}}
@include('layouts.navigation.user.frota')

{{-- Módulo Manutenções --}}
@include('layouts.navigation.user.manutencoes')

{{-- Módulo Abastecimentos --}}
@include('layouts.navigation.user.abastecimentos')

{{-- Módulo Agendamentos --}}
@include('layouts.navigation.user.agendamentos')

{{-- Módulo Documentos --}}
@include('layouts.navigation.user.documentos')

{{-- Módulo Usuários --}}
@include('layouts.navigation.user.usuarios')

{{-- Módulo Cadastros --}}
@include('layouts.navigation.user.cadastros')

{{-- Módulo Configurações --}}
@include('layouts.navigation.user.parametros')
