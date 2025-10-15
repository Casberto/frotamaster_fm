<x-guest-layout>
    <div class="w-full sm:max-w-md bg-white shadow-2xl rounded-2xl overflow-hidden">
        <!-- Cabeçalho Azul -->
        <div class="bg-blue-600 px-6 py-8 text-center">
            <a href="/" class="inline-block mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-16 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-white mt-4">Crie sua Nova Senha</h1>
            <p class="text-blue-200 mt-1 text-sm">Escolha uma senha forte e segura.</p>
        </div>
        
        <!-- Formulário -->
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- E-mail -->
                <div>
                    <x-input-label for="email" value="E-mail" class="font-semibold text-gray-700"/>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                           </svg>
                        </span>
                        <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Nova Senha -->
                <div>
                    <x-input-label for="password" value="Nova Senha" class="font-semibold text-gray-700"/>
                     <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                           </svg>
                        </span>
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••••" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirmar Nova Senha -->
                <div>
                    <x-input-label for="password_confirmation" value="Confirme a Nova Senha" class="font-semibold text-gray-700"/>
                     <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                           </svg>
                        </span>
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••••" class="w-full pl-10 py-3 text-base"/>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <x-primary-button class="w-full justify-center text-base py-3">
                        Redefinir Senha
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

