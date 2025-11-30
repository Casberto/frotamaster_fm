<div class="space-y-6">
    {{-- 4.1 Cards de Indicadores --}}
    @include('dashboard.components.abastecimentos.cards')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 4.3 Gráficos --}}
        <div class="lg:col-span-3">
             @include('dashboard.components.abastecimentos.charts')
        </div>
    </div>

    {{-- 4.2 Lista de Abastecimentos --}}
    @include('dashboard.components.abastecimentos.list')
</div>
