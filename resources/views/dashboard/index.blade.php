<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </div>
    </x-slot>

    @if (!Auth::user()->id_empresa)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    @else
        {{-- CORREÇÃO: Adicionado x-data para controlar o estado do accordion da lista de veículos --}}
        <div class="space-y-8" x-data="{ openVeiculo: null }">
            @include('dashboard.components.summary-cards')

            @include('dashboard.components.analysis-cards')
            
            @include('dashboard.components.charts')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:items-start">
                @include('dashboard.components.fleet-list')

                @include('dashboard.components.upcoming-reminders')
            </div>
        </div>

        @push('modals')
            @include('dashboard.components.vehicle-history-modal')
            @include('dashboard.components.vehicle-analysis-modal')
        @endpush
    @endif
</x-app-layout>

