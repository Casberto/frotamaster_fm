<div>
    <button @click="open = (open === 'admin' ? '' : 'admin')" class="sidebar-link w-full flex justify-between items-center">
        <div class="flex items-center">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                </svg>
            </span>
            <span>Administrativo</span>
        </div>
        <svg :class="{'rotate-180': open === 'admin'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div x-show="open === 'admin'" x-transition class="pl-8 space-y-1 mt-1">
        <a href="{{ route('admin.empresas.index') }}" class="sidebar-submenu-link @if(request()->routeIs('admin.empresas.*')) active @endif">Gerenciar Empresas</a>
        <a href="#" class="sidebar-submenu-link disabled">Gerenciar Licenças</a>
    </div>
</div>
