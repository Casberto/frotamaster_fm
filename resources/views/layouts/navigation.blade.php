{{--
    Sidebar Frotamaster com Layout Flex para Rolagem
--}}
<div x-data="{ open: '{{ request()->routeIs('dashboard') ? '' : (explode('.', request()->route()->getName())[0] ?? '') }}' }" class="bg-gray-800 text-white h-full flex flex-col">

    <!-- Logo (Cabeçalho Fixo) -->
    <div class="p-4 flex justify-center shrink-0">
        <a href="/">
            <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-20 w-auto">
        </a>
    </div>

    <!-- Campo de Busca -->
    <div class="px-4 mb-2">
        <div class="relative">
            <input type="text" 
                   id="sidebar-search" 
                   placeholder="Buscar..." 
                   class="w-full bg-gray-700 text-white text-sm rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 border-none placeholder-gray-400">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Links de Navegação (Área com Rolagem) -->
    <nav class="flex-grow px-4 space-y-2 overflow-y-auto">
        {{-- Link principal do Dashboard --}}
        @if(Auth::user()->temPermissao('DAS001'))
            <x-nav-link-custom :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                <x-slot name="icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </x-slot>
                Dashboard
            </x-nav-link-custom>
        @endif

        {{-- Carrega o menu apropriado com base no perfil do usuário --}}
        @if(auth()->user()->role === 'super-admin')
            @include('layouts.navigation.admin-nav')
        @else
            @include('layouts.navigation.user-nav')
        @endif
    </nav>

    <!-- Usuário e Sair (Rodapé Fixo) -->
    <div class="p-4 border-t border-gray-700 shrink-0">
        @if (isset($activeLicense))
            @php
                $dataVencimento = \Carbon\Carbon::parse($activeLicense->data_vencimento);
                $hoje = \Carbon\Carbon::now();
                $dias_restantes = floor($hoje->diffInDays($dataVencimento, false));
            @endphp

            @if ($dias_restantes <= 10 && $dias_restantes >= 0)
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between w-full px-4 py-3 rounded-lg
                    {{ $dias_restantes <= 5 ? 'border border-red-500 bg-red-500/20 text-red-300' : 'border border-yellow-400 bg-yellow-400/20 text-yellow-300' }}">
                    
                    <span class="text-sm">
                        A licença expira em
                        <span class="font-bold">{{ $dias_restantes }}</span>
                        {{ $dias_restantes == 1 ? 'dia' : 'dias' }}.
                    </span>
                </div>
            @endif
        @endif 
        <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center w-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span class="truncate">{{ Auth::user()->name }}</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <x-nav-link-custom :href="route('logout')" :active="false" onclick="event.preventDefault(); this.closest('form').submit();">
                <x-slot name="icon">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </x-slot>
                Sair
            </x-nav-link-custom>
        </form>
    </div>
</div>

{{-- Os estilos permanecem aqui para manter a componentização dos links --}}
<style>
    .sidebar-link, .sidebar-submenu-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.2s;
        width: 100%;
        text-align: left;
    }
    .sidebar-link:hover, .sidebar-submenu-link:hover {
        background-color: #4a5568; /* bg-gray-700 */
    }
    .sidebar-link.active, .sidebar-submenu-link.active {
        background-color: #4a5568; /* bg-gray-700 */
        color: #63b3ed; /* text-blue-400 */
        font-weight: 700;
    }
    .sidebar-submenu-link {
        font-size: 0.875rem; /* text-sm */
        padding-left: 1rem;
        display: flex;
        align-items: center;
        width: 100%;
        border-radius: 0.375rem;
    }
    .sidebar-submenu-link.disabled {
        color: #a0aec0; /* text-gray-500 */
        cursor: not-allowed;
        pointer-events: none;
    }
    .sidebar-submenu-link.disabled:hover {
        background-color: transparent;
    }
    a.sidebar-submenu-link {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('sidebar-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const groups = document.querySelectorAll('.menu-item-group');

            groups.forEach(group => {
                const mainText = group.querySelector('.menu-item-text')?.textContent.toLowerCase() || '';
                const submenuItems = group.querySelectorAll('.submenu-item-text');
                let hasSubmenuMatch = false;

                submenuItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    const link = item.closest('a');
                    if (text.includes(query)) {
                        hasSubmenuMatch = true;
                        if (link) link.style.display = '';
                    } else {
                        if (link && query !== '') link.style.display = 'none';
                        else if (link) link.style.display = '';
                    }
                });

                if (query === '') {
                    group.style.display = '';
                    // Reset submenu items display
                    submenuItems.forEach(item => {
                        const link = item.closest('a');
                        if (link) link.style.display = '';
                    });
                } else if (mainText.includes(query) || hasSubmenuMatch) {
                    group.style.display = '';
                    if (hasSubmenuMatch) {
                        // Try to access Alpine data to open the menu
                        if (group.__x) {
                            group.__x.$data.open = true;
                        }
                    }
                } else {
                    group.style.display = 'none';
                }
            });
        });
    });
</script>
