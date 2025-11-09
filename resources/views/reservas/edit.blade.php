<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Reserva') }} #{{ $reserva->res_id }}
        </h2>
    </x-slot>

    {{-- Atributos x-data e x-init removidos do formulário. --}}
    <div class="pb-12">
        <form method="POST" action="{{ route('reservas.update', $reserva) }}" 
              id="reservaForm">
            @csrf
            @method('PUT')

            {{-- O _form.blade.php agora contém o modal --}}
            @include('reservas._form', [
                'reserva' => $reserva, 
                'veiculos' => $veiculos, 
                'motoristas' => $motoristas,
                'fornecedores' => $fornecedores
            ])
            
        </form>
    </div>
</x-app-layout>