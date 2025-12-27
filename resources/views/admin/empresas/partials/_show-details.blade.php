<div class="space-y-6">
    {{-- Estatísticas Principais --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Usuários --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center">
            <span class="text-3xl font-bold text-gray-800">{{ $empresa->users_count }}</span>
            <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">Usuários</span>
        </div>
        
        {{-- Veículos --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center">
            <span class="text-3xl font-bold text-gray-800">{{ $empresa->veiculos_count }}</span>
            <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">Veículos</span>
        </div>
        
        {{-- Anexos --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center">
            {{-- Usando o atributo calculado que criamos no Model --}}
            <span class="text-3xl font-bold text-gray-800">{{ $empresa->total_anexos }}</span>
            <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">Anexos</span>
        </div>

         {{-- Licença --}}
         <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center justify-center relative overflow-hidden">
            @php
                $activeLicense = $empresa->activeLicense;
                // Forçando inteiro para evitar decimais
                $daysLeft = $activeLicense ? (int) \Carbon\Carbon::now()->diffInDays($activeLicense->data_vencimento, false) : -1;
                $licenseStatusColor = $daysLeft > 0 ? 'text-green-600' : 'text-red-500';
            @endphp
            @if($activeLicense)
                <span class="text-xl font-bold {{ $licenseStatusColor }}">{{ $daysLeft > 0 ? $daysLeft . ' dias' : 'Vencida' }}</span>
                <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">Licença</span>
            @else
                <span class="text-xl font-bold text-gray-400">--</span>
                <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">Sem Licença</span>
            @endif
        </div>
    </div>

    {{-- Seção de Acesso --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Atividade Recente
        </h3>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-sm text-gray-600">Último acesso registrado</span>
                {{-- Lógica simples para pegar o último login de qualquer usuário da empresa --}}
                @php
                    $lastLogin = $empresa->users()->latest('updated_at')->first()->updated_at ?? null; 
                @endphp
                <span class="text-sm font-medium text-gray-900">
                    {{ $lastLogin ? $lastLogin->diffForHumans() : 'Nunca' }}
                </span>
            </div>
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                <span class="text-sm text-gray-600">Usuários Ativos (Online)</span>
                @php
                    // Verifica se o driver de sessão é database para mostrar a contagem
                    $sessionDriver = config('session.driver');
                    $onlineCount = 0;
                    if ($sessionDriver === 'database') {
                        $onlineCount = \Illuminate\Support\Facades\DB::table('sessions')
                            ->whereIn('user_id', $empresa->users->pluck('id'))
                            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                            ->count();
                    }
                @endphp
                <span class="text-sm font-medium text-green-600 flex items-center gap-1">
                    @if($sessionDriver === 'database')
                        <span class="w-2 h-2 rounded-full {{ $onlineCount > 0 ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></span>
                        {{ $onlineCount }} online
                    @else
                        <span class="text-gray-400 text-xs">(Requer driver database)</span>
                    @endif
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Data de Cadastro</span>
                <span class="text-sm font-medium text-gray-900">{{ $empresa->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Lista Rápida de Administradores --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
             <h3 class="font-semibold text-gray-800">Administradores</h3>
             <span class="text-xs text-gray-500 bg-white border px-2 py-0.5 rounded-full">{{ $empresa->users->where('role', 'master')->count() }} Masters</span>
        </div>
        <ul class="divide-y divide-gray-100">
            @foreach($empresa->users->whereIn('role', ['master', 'gerente'])->take(5) as $admin)
            <li class="px-6 py-3 hover:bg-gray-50 transition flex items-center gap-3">
                <img src="{{ $admin->profile_photo_url }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $admin->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $admin->email }}</p>
                </div>
                <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-100 text-blue-700 font-bold uppercase">{{ $admin->role }}</span>
            </li>
            @endforeach
            @if($empresa->users->count() > 5)
                <li class="px-6 py-3 text-center bg-gray-50">
                    <span class="text-xs text-gray-500 italic">+ outros usuários...</span>
                </li>
            @endif
        </ul>
    </div>
</div>
