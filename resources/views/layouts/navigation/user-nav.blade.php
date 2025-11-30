{{-- =============================================== --}}
{{-- MENU DO CLIENTE (USUÁRIO MASTER/COMUM) --}}
{{-- =============================================== --}}

{{-- Módulo Reservas (Agendamentos) --}}
@include('layouts.navigation.user.agendamentos')

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

{{-- Módulo Cadastros --}}
@include('layouts.navigation.user.cadastros')

{{-- Módulo Usuários --}}
@include('layouts.navigation.user.usuarios')

{{-- Módulo Configurações --}}
@include('layouts.navigation.user.parametros')
