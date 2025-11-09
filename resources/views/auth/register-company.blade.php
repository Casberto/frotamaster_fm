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
                <div class="form-section">
                    <h3 class="form-section-title">Dados da Empresa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nome_fantasia" class="block font-medium text-sm text-gray-700">Nome Fantasia*</label>
                            <input type="text" name="nome_fantasia" id="nome_fantasia" class="mt-1 block w-full" value="{{ old('nome_fantasia', $empresa->nome_fantasia ?? '') }}" required>
                        </div>
                        <div>
                            <label for="razao_social" class="block font-medium text-sm text-gray-700">Razão Social*</label>
                            <input type="text" name="razao_social" id="razao_social" class="mt-1 block w-full" value="{{ old('razao_social', $empresa->razao_social ?? '') }}" required>
                        </div>
                        <div>
                            <label for="cnpj" class="block font-medium text-sm text-gray-700">CNPJ*</label>
                            <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full" value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required>
                        </div>
                        <div>
                            <label for="email_contato" class="block font-medium text-sm text-gray-700">Email de Contato*</label>
                            <input type="email" name="email_contato" id="email_contato" class="mt-1 block w-full" value="{{ old('email_contato', $empresa->email_contato ?? '') }}" required>
                        </div>
                        <div>
                            <label for="telefone_contato" class="block font-medium text-sm text-gray-700">Telefone de Contato*</label>
                            <input type="text" name="telefone_contato" id="telefone_contato" class="mt-1 block w-full" value="{{ old('telefone_contato', $empresa->telefone_contato ?? '') }}" required>
                        </div>
                    </div>
                </div>

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
                    <div class="p-6 overflow-y-auto text-sm text-gray-700 space-y-4">
                        
                        <h3 class="font-bold text-lg text-gray-900 mb-2">Termos de Uso</h3>
                        <p><strong>Última atualização:</strong> 04 de novembro de 2025</p>
                        <p>Bem-vindo ao Frotamaster ("Plataforma", "Serviço"), um sistema de software como serviço (SaaS) para gestão de frotas. Estes Termos de Uso ("Termos") regem o seu acesso e uso da nossa Plataforma.</p>
                        <p>Ao criar uma conta e utilizar o Frotamaster, você ("Usuário", "Cliente") concorda em cumprir estes Termos.</p>
                        
                        <h4 class="font-semibold text-gray-800 mt-3">1. Definições</h4>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li><strong>Plataforma:</strong> O sistema web Frotamaster, acessível online, incluindo todo o software, dados e documentação associada.</li>
                            <li><strong>Empresa (Cliente):</strong> A pessoa jurídica que se cadastra na Plataforma, identificada pelo seu `id_empresa`.</li>
                            <li><strong>Usuário Master:</strong> O primeiro usuário cadastrado da Empresa, com permissões administrativas totais sobre os dados da sua Empresa.</li>
                            <li><strong>Usuário Leitura/Comum:</strong> Contas secundárias criadas pelo Usuário Master, com permissões limitadas.</li>
                            <li><strong>Super-Admin:</strong> O administrador geral da Plataforma Frotamaster, responsável pela gestão do sistema e cadastro de novas Empresas.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">2. O Serviço</h4>
                        <p>O Frotamaster concede à Empresa uma licença limitada, não exclusiva, intransferível e revogável para usar a Plataforma para fins internos de gestão de frotas (controle de veículos, manutenções, abastecimentos), conforme o plano contratado.</p>

                        <h4 class="font-semibold text-gray-800 mt-3">3. Contas e Responsabilidade</h4>
                        <p><strong>3.1. Cadastro da Empresa:</strong> O cadastro inicial é realizado pelo Super-Admin (ou através deste formulário de auto-cadastro), que cria a Empresa e, automaticamente, o primeiro Usuário Master associado ao e-mail de contato fornecido.</p>
                        <p><strong>3.2. Responsabilidade do Usuário Master:</strong> O Usuário Master é integralmente responsável por:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>Manter a confidencialidade de sua senha e dados de acesso.</li>
                            <li>Todas as atividades que ocorrem sob sua conta.</li>
                            <li><strong>Gerenciar os acessos de outros usuários (Usuários Comuns/Leitura) vinculados à sua Empresa.</strong> O Usuário Master é o único responsável por criar, editar permissões e desativar as contas de seus colaboradores, assumindo total responsabilidade pelas ações praticadas por esses usuários na Plataforma.</li>
                        </ul>
                        <p class="mt-2"><strong>3.3. Arquitetura Multi-Tenant (Multiempresa):</strong> O Usuário reconhece e concorda que:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>A Plataforma opera em um modelo *multi-tenant* com uma base de dados única.</li>
                            <li>Os dados da sua Empresa são logicamente segregados dos dados de outras empresas através do campo `id_empresa`.</li>
                            <li>A Empresa <strong>compartilhará a mesma infraestrutura de servidor e banco de dados</strong> com outras empresas clientes do Frotamaster.</li>
                            <li>O Frotamaster se compromete a empregar as melhores práticas de segurança para garantir que uma empresa não possa, em nenhuma circunstância, acessar os dados de outra.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">4. Uso Aceitável</h4>
                        <p>O Usuário concorda em não utilizar a Plataforma para:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>Qualquer finalidade ilegal ou não autorizada.</li>
                            <li>Tentar obter acesso não autorizado aos sistemas ou redes do Frotamaster.</li>
                            <li>Interferir ou interromper a integridade ou o desempenho do Serviço e seus dados.</li>
                            <li>Realizar engenharia reversa ou tentar extrair o código-fonte do nosso software.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">5. Disponibilidade e Limitação de Responsabilidade</h4>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>O Serviço é fornecido "COMO ESTÁ" e "CONFORME DISPONÍVEL". Não garantimos que o Serviço será ininterrupto, livre de erros ou 100% seguro.</li>
                            <li>O Frotamaster não se responsabiliza por quaisquer danos diretos ou indiretos, perda de dados, lucros cessantes ou interrupção de negócios decorrentes do uso ou da incapacidade de usar a Plataforma.</li>
                            <li>A precisão dos dados inseridos (KM, valores, datas) é de responsabilidade exclusiva do Cliente.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">6. Rescisão</h4>
                        <p>O Frotamaster reserva-se o direito de suspender ou encerrar o acesso do Cliente ao Serviço, sem aviso prévio, em caso de violação destes Termos.</p>
                        
                        <hr class="my-6">

                        <h3 class="font-bold text-lg text-gray-900 mb-2">Política de Privacidade</h3>
                        <p>O Frotamaster ("nós", "nosso") está comprometido em proteger a sua privacidade. Esta Política de Privacidade explica como coletamos, usamos, divulgamos e protegemos as suas informações.</p>

                        <h4 class="font-semibold text-gray-800 mt-3">1. Informações que Coletamos</h4>
                        <p>Coletamos os seguintes tipos de informações:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li><strong>Dados da Empresa:</strong> Informações fornecidas no cadastro, como Nome Fantasia, Razão Social, CNPJ, e-mail de contato e telefone.</li>
                            <li><strong>Dados dos Usuários (Master e Comuns):</strong> Nome, e-mail e senha (armazenada de forma criptografada).</li>
                            <li><strong>Dados Operacionais da Frota:</strong> Informações inseridas pelo Usuário sobre seus veículos (placa, chassi, renavam, modelo), manutenções (datas, serviços, custos) e abastecimentos (data, KM, litros, valores).</li>
                            <li><strong>Dados de Uso:</strong> Coletamos automaticamente informações sobre como você interage com a Plataforma, como endereços IP, tipo de navegador, páginas visitadas e logs de atividades.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">2. Como Usamos as Informações</h4>
                        <p>Usamos as informações coletadas para:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>Fornecer, operar e manter a Plataforma.</li>
                            <li>Gerenciar as contas de usuário e aplicar a segregação de dados (`id_empresa`).</li>
                            <li>Enviar notificações do sistema (ex: alertas de manutenção, atualizações de serviço).</li>
                            <li>Melhorar a Plataforma, analisando como os usuários interagem com ela.</li>
                            <li>Cumprir obrigações legais.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">3. Compartilhamento e Divulgação de Dados</h4>
                        <p>Nós não vendemos, alugamos ou trocamos suas informações pessoais com terceiros para fins de marketing. Podemos compartilhar informações:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4">
                            <li>Com provedores de serviços terceirizados que nos auxiliam na operação da Plataforma (ex: provedores de hospedagem em nuvem).</li>
                            <li>Se exigido por lei, intimação ou outro processo legal.</li>
                        </ul>

                        <h4 class="font-semibold text-gray-800 mt-3">4. Segurança de Dados</h4>
                        <p>Implementamos medidas de segurança técnicas e organizacionais para proteger seus dados, incluindo criptografia SSL, hashing de senhas e segregação lógica estrita dos dados de cada Empresa. O Usuário Master é corresponsável pela segurança ao proteger suas credenciais e gerenciar adequadamente os acessos de seus próprios usuários.</p>

                        <h4 class="font-semibold text-gray-800 mt-3">5. Seus Direitos (LGPD)</h4>
                        <p>Você tem o direito de acessar, corrigir ou solicitar a exclusão dos dados da sua Empresa. O Usuário Master é o controlador dos dados que insere na plataforma (como dados de seus motoristas) e é responsável por atender às solicitações de titulares desses dados.</p>

                        <h4 class="font-semibold text-gray-800 mt-3">6. Contato</h4>
                        <p>Se você tiver dúvidas sobre esta Política de Privacidade, entre em contato conosco pelo e-mail: `privacidade@frotamaster.com`.</p>

                        <hr class="my-6">

                        <h3 class="font-bold text-lg text-gray-900 mb-2">Consentimento Final</h3>
                        <p>Ao clicar em "Cadastrar e Acessar", o Usuário declara que leu, compreendeu e concorda integralmente com os **Termos de Uso** e a **Política de Privacidade** descritos acima. O Usuário está ciente e concorda especificamente que:</p>
                        <ul class="list-disc list-inside space-y-1 pl-4 font-medium">
                            <li>A plataforma Frotamaster é **multiempresa** e seus dados residirão em uma infraestrutura compartilhada, embora logicamente separados.</li>
                            <li>O **Usuário Master** é o único e total responsável pela gestão de acesso e pelas ações de todos os usuários vinculados à sua conta de empresa.</li>
                        </ul>

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
