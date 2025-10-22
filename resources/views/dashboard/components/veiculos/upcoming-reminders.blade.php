{{-- resources/views/dashboard/components/veiculos/upcoming-reminders.blade.php --}}
{{-- Este componente exibe a lista de próximos lembretes de manutenção. --}}

<div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Próximos Lembretes</h3>
     <div class="space-y-4">
        @forelse ($proximosLembretes ?? [] as $lembrete)
            <div class="border-l-4 border-yellow-400 pl-4 py-2">
                <p class="font-semibold text-gray-800">
                    Manutenção {{ $lembrete->man_tipo }}
                </p>
                <p class="text-sm text-gray-600">{{ $lembrete->veiculo->vei_placa ?? 'N/A' }} - {{ $lembrete->veiculo->vei_modelo ?? 'N/A' }}</p>
                <p class="text-sm text-yellow-600 font-medium">
                    Agendada para: {{ $lembrete->man_data_inicio->format('d/m/Y') }}
                </p>
            </div>
        @empty
            <p class="text-center text-gray-500">Nenhum lembrete futuro.</p>
        @endforelse
    </div>
</div>

