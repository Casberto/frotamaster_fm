<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Solicitar Nova Reserva') }}
        </h2>
    </x-slot>

    {{-- 
      Container py-12 removido para diminuir espaço.
      Atributos x-data e x-init removidos do formulário.
    --}}
    <div class="pb-12">
        <form method="POST" action="{{ route('reservas.store') }}" 
              id="reservaForm">
            @csrf

            {{-- O _form.blade.php agora contém o modal --}}
            @include('reservas._form', [
                'reserva' => new \App\Models\Reserva(), 
                'veiculos' => $veiculos, 
                'motoristas' => $motoristas,
                'fornecedores' => $fornecedores
            ])

        </form>
    </div>
</x-app-layout>