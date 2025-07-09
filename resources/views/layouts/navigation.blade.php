<nav class="sidebar h-full flex flex-col justify-between">
    <div>
        <!-- Logo -->
        <div class="p-4 flex items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a3.375 3.375 0 003.375-3.375h1.5a1.125 1.125 0 011.125 1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m15.75 0v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125 1.125v-1.5c0-.621.504-1.125 1.125-1.125h1.5a3.375 3.375 0 003.375-3.375V6.375c0-1.036-.84-1.875-1.875-1.875H3.375A1.875 1.875 0 001.5 6.375v1.5c0 1.036.84 1.875 1.875 1.875h1.5c.621 0 1.125.504 1.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125h-1.5a3.375 3.375 0 00-3.375 3.375V18.75c0 .621.504 1.125 1.125 1.125h1.5a1.125 1.125 0 011.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375 3.375h3.375a3.375 3.375 0 003.375-3.375h1.5c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 011.125-1.125h1.5Z" /></svg>
                <h1 class="text-white text-2xl font-bold">Frotamaster</h1>
            </a>
        </div>

        <div class="mt-4 space-y-2">
            <x-nav-link-custom :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3M3.75 3v1.5M20.25 3v1.5M3.75 19.5h16.5M5.25 7.5h13.5" /></svg></x-slot>
                Dashboard
            </x-nav-link-custom>

            @if(auth()->user()->role === 'super-admin')
            <x-nav-link-custom :href="route('admin.empresas.index')" :active="request()->routeIs('admin.empresas.*')">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m6.75 4.5l-1.5-1.5m0 0l-1.5 1.5m1.5-1.5V21" /></svg></x-slot>
                Gerenciar Empresas
            </x-nav-link-custom>
            @endif

            @if(auth()->user()->role !== 'super-admin')
            <x-nav-link-custom :href="route('veiculos.index')" :active="request()->routeIs('veiculos.*')">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5v-1.875a3.375 3.375 0 003.375-3.375h1.5a1.125 1.125 0 011.125 1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375m15.75 0v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125 1.125v-1.5c0-.621.504-1.125 1.125-1.125h1.5a3.375 3.375 0 003.375-3.375V6.375c0-1.036-.84-1.875-1.875-1.875H3.375A1.875 1.875 0 001.5 6.375v1.5c0 1.036.84 1.875 1.875 1.875h1.5c.621 0 1.125.504 1.125 1.125v1.5a1.125 1.125 0 01-1.125 1.125h-1.5a3.375 3.375 0 00-3.375 3.375V18.75c0 .621.504 1.125 1.125 1.125h1.5a1.125 1.125 0 011.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 003.375 3.375h3.375a3.375 3.375 0 003.375-3.375h1.5c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 011.125-1.125h1.5Z" /></svg></x-slot>
                Meus Veículos
            </x-nav-link-custom>
            
            <x-nav-link-custom :href="route('manutencoes.index')" :active="request()->routeIs('manutencoes.*')">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.472-2.472a3.375 3.375 0 00-4.773-4.773L4.75 15.17l2.472-2.472a3.375 3.375 0 004.773-4.773z" /></svg></x-slot>
                Manutenções
            </x-nav-link-custom>

            <x-nav-link-custom :href="route('abastecimentos.index')" :active="request()->routeIs('abastecimentos.*')">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h.01M8.25 12h.01M8.25 16.5h.01M12 7.5h.01M12 12h.01M12 16.5h.01M15.75 7.5h.01M15.75 12h.01M15.75 16.5h.01M4.5 12a7.5 7.5 0 0115 0v2.25a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 14.25V12z" /></svg></x-slot>
                Abastecimentos
            </x-nav-link-custom>
            @endif
        </div>
    </div>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-nav-link-custom :href="route('logout')" :active="false" onclick="event.preventDefault(); this.closest('form').submit();">
                <x-slot name="icon"><svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg></x-slot>
                Sair
            </x-nav-link-custom>
        </form>
    </div>
</nav>
