@props(['isDark'])

@php
    // Configurações de Cores Baseadas no Tema (isDark vem do componente pai)
    $bgDropdown = $isDark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200';
    $textTitle = $isDark ? 'text-gray-100' : 'text-gray-900';
    $textBody = $isDark ? 'text-gray-400' : 'text-gray-600';
    $textMuted = $isDark ? 'text-gray-500' : 'text-gray-400';
    $hoverItem = $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-50';
    $iconColor = $isDark ? 'text-gray-400' : 'text-gray-500';
    $divider = $isDark ? 'border-gray-700' : 'border-gray-100';
@endphp

<div x-data="{
    open: false,
    mobileOpen: false,
    unreadCount: 0,
    notifications: [],
    
    init() {
        this.fetchNotifications();
        setInterval(() => this.fetchNotifications(), 60000);

        this.$watch('open', value => {
            if (window.innerWidth < 768) {
                this.mobileOpen = value;
            }
        });
    },

    toggle() {
        if (window.innerWidth < 768) {
            this.mobileOpen = !this.mobileOpen;
            this.open = false; 
        } else {
            this.open = !this.open;
            this.mobileOpen = false;
        }
    },

    fetchNotifications() {
        fetch('{{ route('notifications.index') }}')
            .then(response => response.json())
            .then(data => {
                this.unreadCount = data.unread_count;
                this.notifications = data.notifications;
            });
    },

    markAsRead(id, link) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification && !notification.read) {
            notification.read = true;
            this.unreadCount = Math.max(0, this.unreadCount - 1);
        }

        fetch(`/notifications/${id}/read`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            if (link && link !== '#') {
                window.location.href = link;
            }
        });
    },

    markAllRead() {
        this.notifications.forEach(n => n.read = true);
        this.unreadCount = 0;
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    },

    remove(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => this.fetchNotifications());
    }
}" 
class="relative"
@click.away="open = false">

    <!-- Botão do Sino -->
    <button @click="toggle()" type="button" class="relative p-2 rounded-lg transition-colors focus:outline-none {{ $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100' }}">
        <svg class="w-6 h-6 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        <span x-show="unreadCount > 0" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="transform scale-0"
              x-transition:enter-end="transform scale-100"
              class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
        </span>
    </button>

    <!-- Dropdown Desktop -->
    <!-- Classes PHP injetadas para cores -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
         class="hidden md:block absolute left-0 top-full mt-2 w-80 lg:w-96 rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none z-[100] border {{ $bgDropdown }}"
         style="display: none;">
        
        <div class="flex items-center justify-between px-4 py-3 border-b rounded-t-xl {{ $divider }} {{ $isDark ? 'bg-gray-800/80' : 'bg-gray-50/80' }}">
            <h3 class="text-sm font-bold {{ $textTitle }}">Notificações</h3>
            <button @click="markAllRead()" x-show="unreadCount > 0" class="text-xs font-medium transition-colors {{ $isDark ? 'text-blue-400 hover:text-blue-300' : 'text-blue-600 hover:text-blue-700' }}">
                Marcar todas como lidas
            </button>
        </div>

        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
            <template x-if="notifications.length === 0">
                <div class="flex flex-col items-center justify-center py-8 text-center px-4">
                    <svg class="w-12 h-12 mb-3 {{ $isDark ? 'text-gray-600' : 'text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm {{ $textMuted }}">Nenhuma notificação no momento.</p>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <div class="relative group border-b last:border-0 transition-colors duration-150 {{ $divider }} {{ $hoverItem }}"
                     :class="{ 
                        '{{ $isDark ? 'bg-blue-900/10' : 'bg-blue-50/60' }}': !notif.read, 
                        '{{ $isDark ? 'bg-gray-800' : 'bg-white' }}': notif.read 
                     }">
                    
                    <div class="p-4 flex items-start gap-4 cursor-pointer" @click="markAsRead(notif.id, notif.link)">
                        <!-- Ícone -->
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="inline-flex items-center justify-center h-9 w-9 rounded-full shadow-sm ring-1 ring-black/5"
                                  :class="{
                                      '{{ $isDark ? 'bg-red-900/20 text-red-400' : 'bg-red-50 text-red-600' }}': notif.type === 'danger',
                                      '{{ $isDark ? 'bg-amber-900/20 text-amber-400' : 'bg-amber-50 text-amber-600' }}': notif.type === 'warning',
                                      '{{ $isDark ? 'bg-blue-900/20 text-blue-400' : 'bg-blue-50 text-blue-600' }}': notif.type === 'info',
                                      '{{ $isDark ? 'bg-emerald-900/20 text-emerald-400' : 'bg-emerald-50 text-emerald-600' }}': notif.type === 'success'
                                  }">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path x-show="notif.icon === 'wrench-screwdriver'" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path x-show="notif.icon === 'wrench-screwdriver'" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path x-show="notif.icon !== 'wrench-screwdriver'" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </span>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold {{ $textTitle }}" x-text="notif.title"></p>
                            <p class="text-sm {{ $textBody }} mt-0.5 line-clamp-2 leading-snug" x-text="notif.message"></p>
                            <p class="text-xs {{ $textMuted }} mt-1.5 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-text="notif.date_human"></span>
                            </p>
                        </div>

                        <!-- Read Indicator -->
                        <div class="flex-shrink-0 self-center" x-show="!notif.read">
                            <span class="block h-2.5 w-2.5 rounded-full bg-blue-500 ring-2 ring-white {{ $isDark ? 'ring-gray-800' : 'ring-white' }}"></span>
                        </div>
                    </div>
                
                     <!-- Delete Action (Corrigido para usar classes Tailwind dinâmicas no hover) -->
                     <!-- Como não dá pra injetar hover fácil via PHP dentro de :class do Alpine, usamos classes padrão compatíveis -->
                    <button @click.stop="remove(notif.id)" class="absolute top-3 right-3 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 {{ $isDark ? 'hover:bg-red-900/20' : '' }} rounded-full opacity-0 group-hover:opacity-100 transition-all focus:opacity-100 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    <!-- Modal Mobile -->
    <template x-teleport="body">
        <div x-show="mobileOpen" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:hidden"
             style="display: none;">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                 x-show="mobileOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileOpen = false"></div>

            <div class="relative w-full max-w-sm rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[85vh] {{ $bgDropdown }}"
                 x-show="mobileOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                
                <div class="px-5 py-4 border-b flex items-center justify-between {{ $divider }} {{ $isDark ? 'bg-gray-800' : 'bg-white' }}">
                    <h2 class="text-lg font-bold {{ $textTitle }}">Notificações</h2>
                    <button @click="mobileOpen = false" class="{{ $textMuted }} hover:text-gray-500 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar {{ $isDark ? 'bg-gray-900/50' : 'bg-gray-50' }}">
                     <template x-for="notif in notifications" :key="notif.id">
                        <div class="p-4 border-b transition-colors {{ $divider }} {{ $isDark ? 'bg-gray-800 active:bg-gray-700/50' : 'bg-white active:bg-gray-50' }}" 
                             @click="markAsRead(notif.id, notif.link)">
                            <div class="flex gap-4">
                                <!-- Ícone Mobile -->
                                <div class="flex-shrink-0 mt-1">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full"
                                          :class="{
                                              '{{ $isDark ? 'bg-red-900/30 text-red-400' : 'bg-red-100 text-red-600' }}': notif.type === 'danger',
                                              '{{ $isDark ? 'bg-amber-900/30 text-amber-400' : 'bg-amber-100 text-amber-600' }}': notif.type === 'warning',
                                              '{{ $isDark ? 'bg-blue-900/30 text-blue-400' : 'bg-blue-100 text-blue-600' }}': notif.type === 'info',
                                              '{{ $isDark ? 'bg-green-900/30 text-green-400' : 'bg-green-100 text-green-600' }}': notif.type === 'success'
                                          }">
                                          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path x-show="notif.icon === 'wrench-screwdriver'" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.702.127 1.5.876 2.183 2.69 1.508 4.051a2.155 2.155 0 01-1.226 1.096m-1.984-5.274l-6.837 5.63a2.548 2.548 0 01-3.586-3.586l4.655-5.653c.697-.847 1.776-1.18 2.766-1.018m-6.86 1.353C4.248 7.379 2.156 5.864 1.272 4.095c-.473-.949-.07-2.115.897-2.597.967-.482 2.133-.081 2.606.866.884 1.768.225 3.996.936 6.309z" />
                                                <path x-show="notif.icon !== 'wrench-screwdriver'" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                          </svg>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-sm font-bold {{ $textTitle }} line-clamp-1" x-text="notif.title"></h4>
                                        <span x-show="!notif.read" class="ml-2 w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></span>
                                    </div>
                                    <p class="text-sm {{ $textBody }} mt-1 leading-relaxed" x-text="notif.message"></p>
                                    <p class="text-xs {{ $textMuted }} mt-2" x-text="notif.date_human"></p>
                                </div>
                            </div>
                        </div>
                     </template>

                     <template x-if="notifications.length === 0">
                        <div class="py-12 text-center px-6">
                            <p class="{{ $textMuted }}">Tudo limpo por aqui! Nenhuma notificação pendente.</p>
                        </div>
                     </template>
                </div>

                <div class="p-4 border-t {{ $divider }} {{ $isDark ? 'bg-gray-800' : 'bg-white' }}" x-show="unreadCount > 0">
                    <button @click="markAllRead(); mobileOpen = false" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-colors text-sm">
                        Marcar todas como lidas
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
