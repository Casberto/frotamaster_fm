@if(Auth::user()->temPermissao('MOT001') || Auth::user()->temPermissao('MOT002'))
<div x-data="{ open: false }" class="menu-item-group">
    <button @click="open = !open" class="sidebar-link w-full flex justify-between items-center">
        <div class="flex items-center">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </span>
            <span class="menu-item-text">Motoristas</span>
        </div>
        <svg :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div x-show="open" x-transition class="pl-8 space-y-1 mt-1 submenu-container">
        @if(Auth::user()->temPermissao('MOT001'))
        <a href="{{ route('motoristas.index') }}" class="sidebar-submenu-link @if(request()->routeIs('motoristas.index')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg></span>
            <span class="submenu-item-text">Listar Motoristas</span>
        </a>
        @endif
        @if(Auth::user()->temPermissao('MOT002'))
        <a href="{{ route('motoristas.create') }}" class="sidebar-submenu-link @if(request()->routeIs('motoristas.create')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg></span>
            <span class="submenu-item-text">Novo Motorista</span>
        </a>
        @endif
    </div>
</div>
@endif
