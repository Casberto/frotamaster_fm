<x-guest-layout>
    {{-- 
        Este bloco de estilo é adicionado para sobrescrever os estilos do formulário 
        incluído ('admin.empresas._form'), garantindo a consistência visual
        com a tela de login sem modificar o arquivo original do formulário.
    --}}
    <style>
        #company-register-form .form-section label,
        #company-register-form .form-section .form-section-title {
            color: #e2e8f0;
        }
        #company-register-form .form-section .form-section-title {
            border-bottom-color: #4a5568;
        }
        #company-register-form .form-section input,
        #company-register-form .form-section select {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }
        #company-register-form .form-section input:focus,
        #company-register-form .form-section select:focus {
            --tw-ring-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px var(--tw-ring-color) !important;
        }
        #company-register-form .flex.items-center.justify-end.mt-8 {
            display: none;
        }
    </style>

    <div class="w-full min-h-screen flex flex-col justify-center sm:min-h-0 sm:h-auto sm:max-w-2xl px-6 py-8 bg-gray-800 sm:shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col items-center">
            <a href="/">
                <img src="{{ asset('img/logo.png') }}" alt="Frotamaster Logo" class="w-50 h-20">
            </a>
            <h1 class="text-white text-3xl font-bold mt-2">Cadastre sua Empresa</h1>
            <p class="text-gray-400 mt-1">Comece a gerenciar sua frota hoje mesmo.</p>
        </div>

        @if (session('error'))
            <div class="mt-4 bg-red-500 text-white p-4 rounded-md">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('company.store') }}" class="mt-6">
            @csrf
            
            <div id="company-register-form">
                @php $empresa = new \App\Models\Empresa(); @endphp
                @include('admin.empresas._form', ['empresa' => $empresa])
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-3 bg-gray-200 border border-transparent rounded-md font-semibold text-base text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Cadastrar e Acessar
                </button>
            </div>

            <div class="text-center mt-6">
                <a class="underline text-sm text-gray-400 hover:text-gray-200" href="{{ route('login') }}">
                    Já possui uma conta? Acesse aqui
                </a>
            </div>
        </form>
    </div>

    {{-- SCRIPTS PARA A MÁSCARA --}}
    {{-- 1. Incluindo a biblioteca jQuery (necessária para o plugin de máscara) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    {{-- 2. Incluindo a biblioteca jQuery Mask --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    {{-- 3. Seu script de inicialização da máscara (apenas para os campos desta tela) --}}
    <script type="text/javascript">
        $(document).ready(function(){
            // Máscara para o campo CNPJ
            $('#cnpj').mask('00.000.000/0000-00', {reverse: true});

            // Máscara para o campo de Telefone
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
</x-guest-layout>
