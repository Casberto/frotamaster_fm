<div class="space-y-6">
    {{-- 5.1 Cards de Indicadores --}}
    @include('dashboard.components.reservas.cards')

    {{-- 5.2 Calendário de Visualização --}}
    @include('dashboard.components.reservas.calendar')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 5.3 Gráficos --}}
        <div class="lg:col-span-3">
             @include('dashboard.components.reservas.charts')
        </div>
    </div>

    {{-- 5.4 Lista de Reservas --}}
    @include('dashboard.components.reservas.list')
</div>
