{{--
    Sidebar Frotamaster com Layout Flex para Rolagem
--}}
@php
    $theme = $currentTheme ?? 'light';
    $isDark = $theme === 'dark';
    $bgColor = $isDark ? 'bg-[#0f172a]' : 'bg-white'; // Dark: slate-900 like
    $textColor = $isDark ? 'text-gray-300' : 'text-gray-600';
    $borderColor = $isDark ? 'border-gray-700' : 'border-gray-200';
    $hoverColor = $isDark ? 'hover:bg-gray-800' : 'hover:bg-gray-100';
@endphp

<div x-data="{ 
    open: '{{ request()->routeIs('dashboard') ? '' : (explode('.', request()->route()->getName())[0] ?? '') }}',
    sidebarTheme: '{{ $theme }}',
    toggleTheme() {
        this.sidebarTheme = this.sidebarTheme === 'dark' ? 'light' : 'dark';
        fetch('{{ route('theme.switch') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: this.sidebarTheme })
        }).then(() => {
            window.location.reload(); // Recarrega para aplicar tema globalmente se necessário
        });
    }
}" 
class="{{ $bgColor }} {{ $textColor }} h-full flex flex-col border-r {{ $borderColor }} transition-colors duration-300">

    <!-- Estilos para Marquee -->
    <style>
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        
        .marquee-content {
            display: inline-block;
            animation: marquee 10s linear infinite;
        }
        
        /* Animação simplificada: do lado direito para o esquerdo */
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-150%); }
        }
    </style>

    <!-- Header: Logo e Empresa -->
    <div class="p-6 flex flex-col items-center shrink-0 border-b {{ $borderColor }}">
        <a href="/" class="flex items-center gap-3 w-full justify-center">
            <div class="flex items-center justify-center h-16 w-16 shrink-0">
                <img src="{{ asset('img/logo.svg') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            
            <div class="flex flex-col overflow-hidden w-[140px]">
                <h1 class="font-bold text-lg {{ $isDark ? 'text-white' : 'text-gray-900' }} leading-tight">Frotamaster</h1>
                
                @php
                    $headerSubtitle = Auth::user()->isSuperAdmin() ? 'SUPER ADMIN' : (Auth::user()->empresa ? Auth::user()->empresa->nome_fantasia : 'PARTICULAR');
                    // Reduzido para 13 caracteres para garantir ativação
                    $shouldScroll = mb_strlen($headerSubtitle) > 13;
                @endphp

                @if($shouldScroll)
                    <div class="marquee-container w-full">
                        <p class="marquee-content text-xs font-medium text-blue-500 tracking-wide uppercase">
                            {{ $headerSubtitle }}
                        </p>
                    </div>
                @else
                    <p class="text-xs font-medium text-blue-500 tracking-wide uppercase truncate" title="{{ $headerSubtitle }}">
                        {{ $headerSubtitle }}
                    </p>
                @endif
            </div>
        </a>
    </div>

    <!-- Barra de Ferramentas: Busca + Notificações + Tema -->
    <div class="px-4 py-3 flex items-center justify-between gap-2">
        <!-- Busca -->
        <div class="relative flex-grow">
            <input type="text" 
                   id="sidebar-search" 
                   placeholder="Buscar..." 
                   class="w-full {{ $isDark ? 'bg-gray-800 text-gray-200' : 'bg-gray-100 text-gray-700' }} text-sm rounded-lg pl-9 pr-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 border-none placeholder-gray-400">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Notificações Dropdown -->
        <x-notifications-menu :is-dark="$isDark" />
    </div>

    <!-- Navegação -->
    <nav class="flex-grow px-3 space-y-1 overflow-y-auto mt-2 custom-scrollbar">
        <!-- Principal -->
        <p class="px-3 text-xs font-semibold {{ $isDark ? 'text-gray-500' : 'text-gray-400' }} uppercase tracking-wider mb-2 mt-4">Principal</p>
        
        @if(Auth::user()->temPermissao('DAS001'))
            <x-nav-link-custom :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')">
                <x-slot name="icon">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </x-slot>
                Dashboard
            </x-nav-link-custom>
        @endif

        {{-- Carrega menus adicionais --}}
        @if(auth()->user()->role === 'super-admin')
            @include('layouts.navigation.admin-nav')
        @else
            @include('layouts.navigation.user-nav')
        @endif
    </nav>

    <!-- Footer: Usuário e Ações -->
    <div class="p-4 border-t {{ $borderColor }} shrink-0">
        <!-- Licença Aviso -->
        @if (isset($activeLicense))
            @php
                $dataVencimento = \Carbon\Carbon::parse($activeLicense->data_vencimento);
                $dias_restantes = floor(\Carbon\Carbon::now()->diffInDays($dataVencimento, false));
            @endphp
            @if ($dias_restantes <= 10 && $dias_restantes >= 0)
                <div class="mb-3 px-3 py-2 rounded-md text-xs font-medium border {{ $dias_restantes <= 5 ? 'border-red-500/50 bg-red-500/10 text-red-500' : 'border-yellow-500/50 bg-yellow-500/10 text-yellow-500' }}">
                    Licença expira em {{ $dias_restantes }} dias.
                </div>
            @endif
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full object-cover border-2 {{ $isDark ? 'border-gray-600' : 'border-gray-200' }}" 
                     src="{{ Auth::user()->profile_photo_url }}" 
                     alt="{{ Auth::user()->name }}">
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium {{ $isDark ? 'text-white' : 'text-gray-900' }} truncate">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs {{ $isDark ? 'text-gray-500' : 'text-gray-500' }} truncate">
                    {{ Auth::user()->email }}
                </p>
            </div>
            
            <!-- Toggle Theme (Mini) -->
            <button @click="toggleTheme()" class="p-1.5 rounded-md {{ $hoverColor }} transition-colors text-gray-400 hover:text-yellow-500" title="Alternar Tema">
                <template x-if="sidebarTheme === 'dark'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </template>
                <template x-if="sidebarTheme !== 'dark'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </template>
            </button>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="flex items-center w-full px-2 py-2 text-xs font-medium {{ $isDark ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-900' }} transition-colors rounded-md hover:bg-opacity-10 {{ $isDark ? 'hover:bg-white' : 'hover:bg-black' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Sair
            </button>
        </form>
    </div>
</div>

<style>
    /* Custom Scrollbar for Navbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.8);
    }
    
    /* Search Logic Helper */
    .menu-item-group[style*="display: none"] {
        display: none !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search Logic
        const searchInput = document.getElementById('sidebar-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const groups = document.querySelectorAll('.menu-item-group'); // Ensure your Nav Items have this class if you want search to work effectively
                // ... (Logic would need standardization of Nav Items to classes)
                // For now, simple text match:
                const nav = document.querySelector('nav');
                const links = nav.querySelectorAll('a');
                
                links.forEach(link => {
                    if (link.textContent.toLowerCase().includes(query)) {
                        link.style.display = '';
                        // Open parent if submenu (implementation dependent)
                    } else {
                        link.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.8);
    }
    
    /* Search Logic Helper */
    .menu-item-group[style*="display: none"] {
        display: none !important;
    }

    /* Sidebar Links Styles - Theme Aware by Variables or Context */
    .sidebar-link, .sidebar-submenu-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem; /* 12px 16px */
        border-radius: 0.5rem; /* 8px */
        transition: all 0.2s;
        width: 100%;
        text-align: left;
        margin-bottom: 0.25rem;
    }

    /* DARK MODE STYLES (Default legacy or applied via wrapper) */
    .bg-\[\#0f172a\] .sidebar-link:hover, 
    .bg-\[\#0f172a\] .sidebar-submenu-link:hover {
        background-color: rgba(30, 41, 59, 0.8); /* slate-800 */
        color: #e2e8f0; /* slate-200 */
    }
    .bg-\[\#0f172a\] .sidebar-link.active, 
    .bg-\[\#0f172a\] .sidebar-submenu-link.active {
        background-color: #2563eb; /* blue-600 */
        color: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    /* LIGHT MODE STYLES */
    .bg-white .sidebar-link:hover, 
    .bg-white .sidebar-submenu-link:hover {
        background-color: #f3f4f6; /* gray-100 */
        color: #111827; /* gray-900 */
    }
    .bg-white .sidebar-link.active, 
    .bg-white .sidebar-submenu-link.active {
        background-color: #dbeafe; /* blue-100 */
        color: #1d4ed8; /* blue-700 */
    }

    /* Submenu Link specifics */
    .sidebar-submenu-link {
        font-size: 0.875rem; /* text-sm */
        padding-left: 2.5rem; /* Indent */
    }
    
    /* Adjust icons in active state if needed */
    .sidebar-link.active svg {
        /* stroke-width: 2.5; */
    }
</style>

