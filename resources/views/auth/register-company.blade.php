<x-guest-layout>
    <div class="w-full sm:max-w-2xl bg-white shadow-2xl rounded-2xl overflow-hidden">
        <!-- Cabeçalho Azul -->
        <div class="bg-blue-600 px-6 py-8 text-center">
            <a href="/" class="inline-block mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="h-16 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-white mt-4">Cadastre sua Empresa</h1>
            <p class="text-blue-200 mt-1 text-sm">Comece a gerenciar sua frota hoje mesmo.</p>
        </div>

        <!-- Formulário -->
        <div x-data="{ openModal: false }" class="p-6 sm:p-8">
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                    <p class="font-bold">Ocorreu um erro</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('company.store') }}">
                @csrf

                @php $empresa = new \App\Models\Empresa(); @endphp
                @include('admin.empresas._form', ['empresa' => $empresa])

                {{-- Termos de Uso --}}
                <div class="mt-6 text-center text-sm text-gray-500">
                    Ao continuar, você concorda com nossos
                    <a href="#" @click.prevent="openModal = true" class="underline text-blue-600 hover:text-blue-800">
                        Termos de Uso e Política de Privacidade
                    </a>.
                </div>

                <div class="mt-6">
                    <x-primary-button class="w-full justify-center text-base py-3 group">
                        <span>Cadastrar e Acessar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </x-primary-button>
                </div>

                <div class="text-center mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        Já possui uma conta? Acesse aqui
                    </a>
                </div>
            </form>

            <!-- Modal dos Termos de Uso -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" x-cloak>
                <div @click.away="openModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h2 class="text-xl font-semibold">Termos de Uso e Política de Privacidade</h2>
                        <button @click="openModal = false" class="text-gray-500 hover:text-gray-800">&times;</button>
                    </div>
                    <div class="p-6 overflow-y-auto">
                        <h3 class="font-bold mb-2">Termos de Uso</h3>
                        <p class="text-gray-600 mb-4 text-sm">
                            [Aqui entrará o texto completo dos Termos de Uso do Frotamaster.]
                        </p>
                        <h3 class="font-bold mb-2">Política de Privacidade</h3>
                        <p class="text-gray-600 text-sm">
                            [Aqui entrará o texto da Política de Privacidade.]
                        </p>
                    </div>
                    <div class="p-4 border-t bg-gray-50 text-right">
                        <x-secondary-button @click="openModal = false">
                            Fechar
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- SCRIPTS PARA A MÁSCARA --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
            var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(SPMaskBehavior.apply({}, arguments), options);
                }
            };
            $('#telefone_contato').mask(SPMaskBehavior, spOptions);
        });
    </script>
    @endpush
</x-guest-layout>

