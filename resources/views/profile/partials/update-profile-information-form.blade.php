<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Atualize as informações de perfil e o endereço de e-mail da sua conta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    
            <div x-data="{ 
            photoName: null, 
            photoPreview: null, 
            showMenu: false,
            showModal: false,
            deletePhoto: false,   // Flag para remoção
            
            openCamera() {
                // Simula clique no input de câmera (se separado) ou mesmo input file
                // Para simplificar, vamos usar o mesmo input, mas o atributo capture poderia ser setado dinamicamente
                // Mas browsers desktop ignoram capture. Mobile usa.
                // Vamos focar em acionar o input file principal por enquanto.
                // Mas o usuário pediu 4 opções.
                document.getElementById('photo_camera').click();
            },

            removePhoto() {
               this.photoPreview = null;
               this.deletePhoto = true; // Marca para deletar no backend
               this.showMenu = false;
            }
        }" class="col-span-6 sm:col-span-4 relative">
        
            <!-- Inputs Ocultos -->
            <input type="file" id="photo" class="hidden" name="photo" x-ref="photo" accept="image/*"
                x-on:change="
                    photoName = $refs.photo.files[0].name;
                    deletePhoto = false; // Se selecionou nova, não deleta
                    const reader = new FileReader();
                    reader.onload = (e) => { photoPreview = e.target.result; };
                    reader.readAsDataURL($refs.photo.files[0]);
                    showMenu = false;
                " />

            <!-- Input específico para Câmera (Mobile) - aceita capture="user" -->
            <input type="file" id="photo_camera" class="hidden" name="photo_camera" x-ref="photo_camera" accept="image/*" capture="user"
                x-on:change="
                     // Transfere o arquivo para o input principal logicamente ou apenas usa este
                     // Como o form só envia um 'photo', precisamos setar o input 'photo' ou renomear este.
                     // Hack: Vamos usar JS para processar o preview igual. 
                     // No envio, o form pegará o ultimo input com name='photo'.
                     // Melhor: ter name='photo' aqui também? O PHP pega o ultimo.
                     photoName = $refs.photo_camera.files[0].name;
                     deletePhoto = false;
                     const reader = new FileReader();
                     reader.onload = (e) => { photoPreview = e.target.result; };
                     reader.readAsDataURL($refs.photo_camera.files[0]);
                     showMenu = false;
                     
                     // Transfer file to main input? Not possible (security).
                     // Solution: Change name of this input to 'photo' too? Or simple fallback.
                     // Vamos usar name='photo' aqui também. O backend pegará um deles.
                " />

            <!-- Flag de Deletar -->
            <input type="hidden" name="delete_photo" x-model="deletePhoto" value="0">

            <x-input-label for="photo" :value="__('Foto de Perfil')" class="mb-2" />

            <div class="flex items-center gap-6">
                
                <!-- Container da Imagem com Overlay -->
                <div class="relative group h-32 w-32 rounded-full overflow-hidden cursor-pointer shadow-lg"
                     @click="showMenu = !showMenu"
                     @click.away="showMenu = false">
                     
                     <!-- Imagem Atual -->
                    <div x-show="! photoPreview && ! deletePhoto" class="h-full w-full">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    </div>

                    <!-- Placeholder Padrão (se deletado ou sem foto) -->
                    <div x-show="deletePhoto && ! photoPreview" class="h-full w-full bg-gray-200 flex items-center justify-center">
                        <img src="{{ asset('img/default-avatar.svg') }}" alt="Default" class="h-full w-full object-cover opacity-75">
                    </div>

                    <!-- Preview da Nova Imagem -->
                    <div x-show="photoPreview" class="h-full w-full bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </div>

                     <!-- Overlay (Hover) -->
                     <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-white text-xs font-medium text-center px-1">Mudar foto<br>do perfil</span>
                     </div>
                </div>

                <!-- Menu Dropdown -->
                <div x-show="showMenu" style="display: none;"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="absolute top-10 left-36 w-48 rounded-md shadow-xl bg-white border border-gray-200 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        
                        <!-- 1. Mostrar Foto -->
                        <button type="button" @click="showModal = true; showMenu = false" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex w-full items-center px-4 py-2 text-sm transition-colors">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Mostrar foto
                        </button>

                        <!-- 2. Tirar Foto (Camera) -->
                        <button type="button" @click="$refs.photo_camera.click(); showMenu = false" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex w-full items-center px-4 py-2 text-sm transition-colors">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Tirar foto
                        </button>

                        <!-- 3. Carregar Foto (File) -->
                        <button type="button" @click="$refs.photo.click(); showMenu = false" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex w-full items-center px-4 py-2 text-sm transition-colors">
                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Carregar foto
                        </button>

                         <!-- 4. Remover Foto -->
                         <button type="button" @click="removePhoto()" class="text-gray-700 hover:bg-gray-50 hover:text-red-600 group flex w-full items-center px-4 py-2 text-sm border-t border-gray-100 transition-colors">
                             <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                             </svg>
                            Remover foto
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Modal de Visualização da Foto -->
             <div x-show="showModal" style="display: none;" 
                  class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  x-transition:leave="transition ease-in duration-200"
                  x-transition:leave-start="opacity-100"
                  x-transition:leave-end="opacity-0">
                  
                  <div class="relative max-w-4xl max-h-screen p-4">
                      <!-- Fechar -->
                      <button type="button" @click="showModal = false" class="absolute -top-10 right-0 text-white hover:text-gray-300 focus:outline-none">
                          <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>
                      
                      <!-- Imagem Grande -->
                      <img :src="photoPreview || '{{ $user->profile_photo_url }}'" alt="Foto de Perfil" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
                  </div>
             </div>
             
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>
        @csrf
        @method('patch')

        {{-- Campo Nome --}}
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Campo E-mail --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Seu endereço de e-mail não foi verificado.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Botão Salvar --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>
