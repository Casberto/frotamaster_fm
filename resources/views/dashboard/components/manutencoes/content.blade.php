<div class="space-y-6">
    {{-- 3.1 Cards de Status --}}
    @include('dashboard.components.manutencoes.cards')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- 3.3 Dashboard de Manutenções (Gráficos) --}}
        <div class="lg:col-span-3">
             @include('dashboard.components.manutencoes.charts')
        </div>
    </div>

    {{-- 3.2 Lista de Manutenções --}}
    @include('dashboard.components.manutencoes.list')
</div>
