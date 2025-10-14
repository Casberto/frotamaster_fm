<div>
    <button @click="open = (open === 'cadastros' ? '' : 'cadastros')" class="sidebar-link w-full flex justify-between items-center">
        <div class="flex items-center">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0M3.75 18H7.5m-3-6h15.75m-15.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0H7.5" />
                </svg>
            </span>
            <span>Cadastros</span>
        </div>
        <svg :class="{'rotate-180': open === 'cadastros'}" class="h-5 w-5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div x-show="open === 'cadastros'" x-transition class="pl-8 space-y-1 mt-1">
        <a href="{{ route('perfis.index') }}" class="sidebar-submenu-link @if(request()->routeIs('perfis.*')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg></span>
            <span>Perfis</span>
        </a>
        <a href="{{ route('servicos.index') }}" class="sidebar-submenu-link @if(request()->routeIs('servicos.*')) active @endif">
            <span class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 2.5a1 1 0 0 1 1 1v.083a6.04 6.04 0 0 0-2 0V3.5a1 1 0 0 1 1-1Zm-2 1v.341C3.67 4.665 2 6.888 2 9.5a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5a6.002 6.002 0 0 0-4-5.659V3.5a2 2 0 1 0-4 0Zm2 1A5 5 0 0 1 12.975 9h-9.95A5 5 0 0 1 8 4.5Zm0 1a.5.5 0 0 0 0 1c1.019 0 1.92.508 2.463 1.286a.5.5 0 1 0 .82-.572A3.996 3.996 0 0 0 8 5.5ZM2.5 11a1.5 1.5 0 0 0 0 3h11a1.5 1.5 0 0 0 0-3h-11ZM2 12.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5Z"/></svg>     
            </span>
            <span>Serviços</span>
        </a>
        <a href="{{ route('fornecedores.index') }}" class="sidebar-submenu-link @if(request()->routeIs('fornecedores.*')) active @endif">
            <span class="mr-3"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg></span>
            <span>Fornecedores</span>
        </a>        
    </div>
</div>