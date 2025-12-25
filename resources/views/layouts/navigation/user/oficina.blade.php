@if(optional(auth()->user()->empresa)->hasModule('oficina'))

@if(isset($isPrestador) && $isPrestador)
    {{-- MENU PLANO PARA PRESTADORES (Sem Dropdown) --}}
    <div class="menu-item-group mt-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 ml-3">Oficina & Serviços</div>
        
        <a href="{{ route('oficina.painel.index') }}" class="sidebar-link mb-1 @if(request()->routeIs('oficina.painel.*')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg></span>
            <span class="menu-item-text">Painel de Serviços</span>
        </a>

        <a href="{{ route('oficina.os.create') }}" class="sidebar-link mb-1 @if(request()->routeIs('oficina.os.create')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
            <span class="menu-item-text">Nova OS Rápida</span>
        </a>

        <a href="{{ route('oficina.financeiro') }}" class="sidebar-link mb-1 @if(request()->routeIs('oficina.financeiro')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
            <span class="menu-item-text">Financeiro</span>
        </a>
        
        <a href="{{ route('oficina.compras.dia') }}" class="sidebar-link mb-1 @if(request()->routeIs('oficina.compras.dia')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg></span>
            <span class="menu-item-text">Lista de Compras</span>
        </a>

        <a href="{{ route('oficina.historico') }}" class="sidebar-link mb-1 @if(request()->routeIs('oficina.historico')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
            <span class="menu-item-text">Histórico / Encerradas</span>
        </a>
    </div>

@else
    {{-- MENU PADRÃO (DROPDOWN) --}}
    <div x-data="{ open: {{ request()->routeIs('oficina.*') ? 'true' : 'false' }} }" class="menu-item-group">
        <button @click="open = !open" class="sidebar-link w-full flex justify-between items-center">
            <div class="flex items-center">
                <span class="mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m3.12 3.12l-2.25 2.25a2.25 2.25 0 01-3.18 0l-1.5-1.5a2.25 2.25 0 010-3.18l2.25-2.25m6.033 8.358l-1.06-1.06m-13.79 3.176l1.061-1.061" />
                    </svg>
                </span>
                <span class="menu-item-text">Oficina & Serviços</span>
            </div>
            <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </button>
        <div x-show="open" x-transition class="pl-8 space-y-1 mt-1 submenu-container">
            
            <a href="{{ route('oficina.painel.index') }}" class="sidebar-submenu-link @if(request()->routeIs('oficina.painel.*')) active @endif">
                <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg></span>
                <span class="submenu-item-text">Painel de Serviços</span>
            </a>

            <a href="{{ route('oficina.os.create') }}" class="sidebar-submenu-link @if(request()->routeIs('oficina.os.create')) active @endif">
                <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                <span class="submenu-item-text">Nova OS Rápida</span>
            </a>

            <a href="{{ route('oficina.financeiro') }}" class="sidebar-submenu-link @if(request()->routeIs('oficina.financeiro')) active @endif">
                <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                <span class="submenu-item-text">Financeiro</span>
            </a>
            
            <a href="{{ route('oficina.compras.dia') }}" class="sidebar-submenu-link @if(request()->routeIs('oficina.compras.dia')) active @endif">
                <span class="submenu-item-text">Lista de Compras</span>
            </a>

            <a href="{{ route('oficina.historico') }}" class="sidebar-submenu-link @if(request()->routeIs('oficina.historico')) active @endif">
                <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                <span class="submenu-item-text">Histórico / Encerradas</span>
            </a>

        </div>
    </div>
@endif

@endif
