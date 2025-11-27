<x-guest-layout>
    <!-- 
        Container Principal
        - Mobile: Fundo branco.
        - Desktop: Fundo escuro (slate-900) para contraste "Enterprise".
    -->
    <div class="min-h-screen flex flex-col justify-center bg-white sm:bg-slate-900" 
         x-data="{
            loading: false,
            tipo: 'PJ', 
            documento: '',
            openModal: false,
            
            init() {
                // Reaplica valor antigo se houver erro de validação
                this.documento = '{{ old('cnpj') }}';
                
                // Monitora mudanças para limpar campo
                this.$watch('tipo', (value) => {
                    this.documento = '';
                });
            },

            mascaraDocumento(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (this.tipo === 'PJ') {
                    // Máscara CNPJ
                    value = value.substring(0, 14);
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    // Máscara CPF
                    value = value.substring(0, 11);
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
                this.documento = value;
            }
         }">
        
        <!-- Cabeçalho -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0 pt-8 sm:pt-0">
            <a href="/" class="flex justify-center mb-6">
                <img class="h-12 w-auto invert sm:invert-0 transition-all duration-300" src="{{ asset('img/logo.png') }}" alt="Frotamaster">
            </a>
            <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-slate-900 sm:text-white">
                Crie sua conta grátis
            </h2>
            <p class="mt-2 text-center text-sm leading-6 text-slate-500 sm:text-slate-400">
                Comece a gerir sua frota em menos de 2 minutos.
            </p>
        </div>

        <!-- Área do Cartão -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[600px] pb-12 sm:pb-0">
            <div class="bg-white px-6 py-10 sm:rounded-2xl sm:px-10 sm:shadow-2xl sm:shadow-black/20 sm:border sm:border-slate-100/10">
                
                <!-- Feedback de Erros -->
                @if (session('error') || $errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Verifique os erros abaixo:</h3>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    @if(session('error')) <li>{{ session('error') }}</li> @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('company.store') }}" class="space-y-6" @submit="loading = true">
                    @csrf

                    <!-- Toggle Tipo de Pessoa -->
                    <div>
                        <label class="text-base font-semibold text-gray-900 block mb-2">Tipo de Cadastro</label>
                        <div class="grid grid-cols-2 gap-3 p-1 bg-slate-100 rounded-lg">
                            <label class="cursor-pointer text-center relative">
                                <input type="radio" name="tipo_pessoa" value="PJ" x-model="tipo" class="peer sr-only">
                                <div class="py-2 text-sm font-medium text-slate-500 rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">
                                    Empresa (PJ)
                                </div>
                            </label>
                            <label class="cursor-pointer text-center relative">
                                <input type="radio" name="tipo_pessoa" value="PF" x-model="tipo" class="peer sr-only">
                                <div class="py-2 text-sm font-medium text-slate-500 rounded-md transition-all peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm">
                                    Pessoa Física
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                        <!-- Documento (CNPJ ou CPF) -->
                        <div class="sm:col-span-2">
                            <label for="cnpj" class="block text-sm font-medium leading-6 text-slate-900">
                                <span x-show="tipo === 'PJ'">CNPJ</span>
                                <span x-show="tipo === 'PF'" style="display: none;">CPF</span>
                            </label>
                            <div class="mt-2 relative">
                                <input type="text" name="cnpj" id="cnpj" x-model="documento" @input="mascaraDocumento" required 
                                    class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    :placeholder="tipo === 'PJ' ? '00.000.000/0000-00' : '000.000.000-00'">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm4 9.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1-.75-.75v-4.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Razão Social / Nome Completo -->
                        <div class="sm:col-span-2">
                            <label for="razao_social" class="block text-sm font-medium leading-6 text-slate-900">
                                <span x-show="tipo === 'PJ'">Razão Social</span>
                                <span x-show="tipo === 'PF'" style="display: none;">Nome Completo</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="razao_social" id="razao_social" value="{{ old('razao_social') }}" required 
                                    class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    placeholder="Como consta no documento">
                            </div>
                        </div>

                        <!-- Nome Fantasia / Apelido -->
                        <div class="sm:col-span-2">
                            <label for="nome_fantasia" class="block text-sm font-medium leading-6 text-slate-900">
                                <span x-show="tipo === 'PJ'">Nome Fantasia</span>
                                <span x-show="tipo === 'PF'" style="display: none;">Nome de Exibição (Apelido)</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="nome_fantasia" id="nome_fantasia" value="{{ old('nome_fantasia') }}" required 
                                    class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    placeholder="Nome visível no sistema">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-1">
                            <label for="email_contato" class="block text-sm font-medium leading-6 text-slate-900">E-mail de Acesso</label>
                            <div class="mt-2">
                                <input type="email" name="email_contato" id="email_contato" value="{{ old('email_contato') }}" required 
                                    class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    placeholder="seu@email.com">
                            </div>
                        </div>

                        <!-- Telefone -->
                        <div class="sm:col-span-1">
                            <label for="telefone_contato" class="block text-sm font-medium leading-6 text-slate-900">WhatsApp</label>
                            <div class="mt-2">
                                <input type="text" name="telefone_contato" id="telefone_contato" value="{{ old('telefone_contato') }}" required 
                                    class="block w-full rounded-lg border-0 py-3 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    placeholder="(00) 00000-0000">
                            </div>
                        </div>
                    </div>

                    <!-- Termos -->
                    <div class="flex items-start pt-2">
                        <div class="flex h-6 items-center">
                            <input id="termos" name="termos" type="checkbox" required class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="termos" class="font-medium text-slate-700 cursor-pointer">Li e aceito os termos</label>
                            <p class="text-slate-500 text-xs">Concordo com os <a href="#" @click.prevent="openModal = true" class="text-blue-600 hover:underline">Termos de Uso</a> e <a href="#" @click.prevent="openModal = true" class="text-blue-600 hover:underline">Política de Privacidade</a>.</p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" :disabled="loading" class="flex w-full justify-center items-center rounded-lg bg-blue-600 px-3 py-3.5 text-sm font-bold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed">
                            <svg x-show="loading" x-cloak class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Criando sua conta...' : 'Criar minha conta grátis'">Criar minha conta grátis</span>
                        </button>
                    </div>
                    
                    <div class="text-center border-t border-slate-100 pt-6">
                        <p class="text-sm text-slate-500">Já tem uma conta? 
                            <a href="{{ route('login') }}" class="font-semibold leading-6 text-blue-600 hover:text-blue-500 transition-colors">
                                Fazer login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
            
            <!-- Copyright Discreto -->
            <p class="mt-8 text-center text-xs leading-5 text-slate-400">
                &copy; {{ date('Y') }} Frotamaster.
            </p>
        </div>

        <!-- Modal Termos -->
        <div x-show="openModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4" x-cloak>
            <div @click.away="openModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex justify-between items-center p-5 border-b bg-slate-50">
                    <h2 class="text-lg font-bold text-slate-900">Termos de Uso e Privacidade</h2>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-8 overflow-y-auto text-sm text-slate-600 leading-relaxed text-justify">
                    <!-- CONTEÚDO DOS TERMOS -->
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-2">TERMOS DE USO</h3>
                    <p class="text-xs text-slate-500 mb-6">Última atualização: 04 de novembro de 2025</p>

                    <p class="mb-4">Bem-vindo ao Frotamaster (“Plataforma”, “Serviço”), um sistema web de software como serviço (SaaS) para gestão de frotas. Estes Termos de Uso (“Termos”) regulam o acesso e a utilização do Serviço. Ao criar uma conta, acessar ou utilizar a Plataforma, você (“Usuário”, “Cliente”) concorda integralmente com estes Termos. Caso não concorde, não utilize o Serviço.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">1. Definições</h4>
                    <p class="mb-2">Para fins destes Termos, aplicam-se as seguintes definições:</p>
                    <ul class="list-disc pl-5 space-y-2 mb-4">
                        <li><strong>Plataforma:</strong> O sistema web Frotamaster, incluindo software, banco de dados, APIs, documentação e quaisquer funcionalidades disponibilizadas online.</li>
                        <li><strong>Empresa (Cliente):</strong> A pessoa jurídica cadastrada na Plataforma, identificada pelo campo id_empresa.</li>
                        <li><strong>Usuário Master:</strong> Primeiro usuário da Empresa, com autonomia total para administrar permissões, acessos, dados e demais usuários.</li>
                        <li><strong>Usuário Comum/Leitura:</strong> Contas secundárias criadas pelo Usuário Master, com permissões restritas conforme definido por este.</li>
                        <li><strong>Super-Admin:</strong> Administrador geral do Frotamaster, responsável pela gestão do sistema, suporte operacional e cadastro de novas Empresas.</li>
                    </ul>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">2. Licença e Uso do Serviço</h4>
                    <p class="mb-2">O Frotamaster concede à Empresa uma licença limitada, não exclusiva, intransferível, revogável e restrita ao território nacional para uso interno da Plataforma, exclusivamente para atividades de gestão de frotas (controle de veículos, manutenções, abastecimentos e funções correlatas), conforme plano contratado.</p>
                    <p class="mb-4">É vedada qualquer utilização que extrapole esse escopo.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">3. Cadastro, Contas e Responsabilidade</h4>
                    
                    <h5 class="font-semibold text-slate-800 mt-4 mb-1">3.1. Cadastro da Empresa</h5>
                    <p class="mb-3">O cadastro é realizado pelo Super-Admin ou via processo de auto-cadastro. A criação da Empresa gera automaticamente um Usuário Master vinculado ao e-mail informado no registro.</p>

                    <h5 class="font-semibold text-slate-800 mt-4 mb-1">3.2. Responsabilidades do Usuário Master</h5>
                    <p class="mb-2">O Usuário Master é o único responsável por:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-3">
                        <li>Proteger credenciais de acesso e manter a confidencialidade de sua senha.</li>
                        <li>Garantir a veracidade e atualização dos dados inseridos.</li>
                        <li>Criar, gerenciar, revisar e remover acessos de seus colaboradores.</li>
                        <li>Responder por todas as ações executadas pelos usuários vinculados à sua Empresa.</li>
                    </ul>
                    <p class="mb-3">Qualquer uso indevido por usuários subordinados será imputado diretamente à Empresa.</p>

                    <h5 class="font-semibold text-slate-800 mt-4 mb-1">3.3. Arquitetura Multi-Tenant</h5>
                    <p class="mb-2">O Usuário reconhece e concorda que:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">
                        <li>O Frotamaster opera sob infraestrutura multi-tenant, com banco de dados único e segregação lógica via id_empresa.</li>
                        <li>A Empresa compartilha ambiente computacional, servidores e tecnologia com outras empresas clientes.</li>
                        <li>São aplicadas medidas destinadas a impedir o acesso cruzado entre empresas, mas tais medidas não caracterizam garantia absoluta contra incidentes externos ou decorrentes de mau uso do Cliente.</li>
                    </ul>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">4. Uso Aceitável</h4>
                    <p class="mb-2">O Usuário se compromete a não utilizar a Plataforma para:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">
                        <li>Práticas ilícitas, fraudulentas ou que violem legislação aplicável.</li>
                        <li>Tentativas de acesso indevido, exploração de vulnerabilidades ou quebra de segurança.</li>
                        <li>Prejuízo à integridade, disponibilidade ou funcionamento da Plataforma.</li>
                        <li>Engenharia reversa, descompilação ou qualquer tentativa de acesso ao código-fonte.</li>
                    </ul>
                    <p class="mb-4">Violação destas regras pode resultar na suspensão ou encerramento imediato da conta.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">5. Disponibilidade, Riscos e Limitações de Responsabilidade</h4>
                    <p class="mb-2">O Serviço é fornecido “NO ESTADO EM QUE SE ENCONTRA” e “CONFORME DISPONÍVEL”. Podem ocorrer interrupções, falhas, indisponibilidades ou erros.</p>
                    <p class="mb-2">O Frotamaster não garante funcionamento ininterrupto, desempenho ideal nem ausência de vulnerabilidades.</p>
                    <p class="mb-2">A Empresa é exclusivamente responsável pela exatidão dos dados inseridos (KM, valores, datas, registros de manutenção etc.).</p>
                    <p class="mb-2">Em nenhuma hipótese o Frotamaster será responsável por danos diretos, indiretos, incidentais, consequenciais, lucros cessantes, perda de dados, paralisação de atividades, prejuízos operacionais ou quaisquer danos similares decorrentes:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">
                        <li>do uso da Plataforma,</li>
                        <li>da incapacidade de utilizá-la,</li>
                        <li>de falhas internas da Empresa,</li>
                        <li>de acessos indevidos oriundos da própria negligência da Empresa sobre suas contas.</li>
                    </ul>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">6. Suspensão e Rescisão</h4>
                    <p class="mb-2">O Frotamaster poderá suspender ou encerrar o acesso do Cliente, a qualquer momento e sem aviso prévio, caso identifique:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">
                        <li>violação destes Termos,</li>
                        <li>uso abusivo,</li>
                        <li>atividade ilegal,</li>
                        <li>risco à segurança da Plataforma.</li>
                    </ul>
                    <p class="mb-8">A rescisão não gera obrigação de indenização ou continuidade do serviço.</p>

                    <hr class="border-slate-200 my-8">

                    <h3 class="text-xl font-bold text-slate-900 mb-2">POLÍTICA DE PRIVACIDADE</h3>
                    <p class="text-xs text-slate-500 mb-6">Última atualização: 04 de novembro de 2025</p>

                    <p class="mb-4">O Frotamaster (“nós”, “nosso”) está comprometido em proteger a privacidade e a segurança dos dados tratados na Plataforma. Esta Política descreve como coletamos, utilizamos, armazenamos e compartilhamos informações, em conformidade com a Lei Geral de Proteção de Dados (LGPD – Lei nº 13.709/2018).</p>
                    <p class="mb-6">Ao utilizar a Plataforma, o Usuário concorda com os termos desta Política.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">1. Informações Coletadas</h4>
                    <p class="mb-2">Coletamos as seguintes categorias de dados:</p>
                    
                    <h5 class="font-semibold text-slate-800 mt-3 mb-1">1.1. Dados da Empresa</h5>
                    <p class="mb-2">Nome Fantasia, Razão Social, CNPJ, e-mail de contato, telefone, informações cadastrais necessárias para criação e operação da conta.</p>

                    <h5 class="font-semibold text-slate-800 mt-3 mb-1">1.2. Dados dos Usuários</h5>
                    <p class="mb-2">Nome, e-mail, senha (armazenada exclusivamente de forma criptografada e não recuperável).</p>

                    <h5 class="font-semibold text-slate-800 mt-3 mb-1">1.3. Dados Operacionais da Frota</h5>
                    <p class="mb-2">Informações inseridas pela Empresa, incluindo: dados de veículos (placa, chassi, renavam, modelo), registros de manutenção, dados de abastecimento, custos, datas, quilometragem e demais informações operacionais.</p>

                    <h5 class="font-semibold text-slate-800 mt-3 mb-1">1.4. Dados de Uso e Logs</h5>
                    <p class="mb-4">Coletamos automaticamente: endereços IP, tipo de dispositivo e navegador, páginas acessadas, data/hora das interações, logs de atividades e auditoria. Esses dados são usados para segurança, funcionamento e melhoria da Plataforma.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">2. Finalidades do Tratamento</h4>
                    <p class="mb-2">Os dados coletados são utilizados para:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-2">
                        <li>Operar e manter a Plataforma.</li>
                        <li>Permitir a autenticação e gestão de contas.</li>
                        <li>Aplicar segregação lógica multiempresa (id_empresa).</li>
                        <li>Emitir alertas, notificações e comunicações essenciais de serviço.</li>
                        <li>Cumprir obrigações legais, fiscais ou regulatórias.</li>
                        <li>Melhorar desempenho, segurança e usabilidade do Serviço.</li>
                        <li>Prevenir fraudes, acessos indevidos e comportamentos abusivos.</li>
                    </ul>
                    <p class="mb-4">Não utilizamos dados para publicidade ou venda a terceiros.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">3. Compartilhamento de Informações</h4>
                    <p class="mb-2">Não vendemos, cedemos ou alugamos dados pessoais para terceiros. O compartilhamento ocorre apenas:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-4">
                        <li>Com provedores de hospedagem e infraestrutura necessários ao funcionamento da Plataforma.</li>
                        <li>Com empresas prestadoras de serviços essenciais à operação (ex.: serviços de e-mail e segurança).</li>
                        <li>Quando exigido por lei, ordem judicial, investigação legal ou autoridade competente.</li>
                        <li>Em situações de auditoria, desde que garantido sigilo.</li>
                    </ul>
                    <p class="mb-4">Todos os terceiros envolvidos estão sujeitos a obrigações de confidencialidade e proteção de dados.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">4. Segurança da Informação</h4>
                    <p class="mb-2">Adotamos medidas técnicas e organizacionais adequadas, incluindo: Criptografia SSL/TLS, hashing seguro de senhas, segregação lógica de dados multiempresa, controles de acesso, logs de auditoria, monitoramento de segurança.</p>
                    <p class="mb-4">Apesar das medidas adotadas, nenhum sistema é 100% livre de riscos. A Empresa é corresponsável ao proteger suas senhas, dispositivos e usuários internos.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">5. Direitos do Titular (LGPD)</h4>
                    <p class="mb-2">Os titulares de dados têm direito de: solicitar confirmação de tratamento, solicitar acesso, correção ou exclusão dos seus dados, solicitar portabilidade, revogar consentimento quando aplicável.</p>
                    <p class="mb-2">O Usuário Master é considerado o controlador dos dados que sua Empresa insere (ex.: dados de motoristas, veículos e registros operacionais). Cabe a ele atender solicitações dos titulares, enquanto o Frotamaster age como operador para esses dados.</p>
                    <p class="mb-4">Dados de cadastro da própria Plataforma (ex.: e-mail do Usuário Master) podem ser solicitados diretamente ao Frotamaster.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">6. Retenção e Eliminação de Dados</h4>
                    <p class="mb-2">Os dados serão mantidos enquanto: a conta estiver ativa, houver obrigações legais de retenção, forem necessários para prestação do Serviço.</p>
                    <p class="mb-4">Após encerramento da conta, os dados poderão ser mantidos por período razoável para cumprimento de obrigações legais, prevenção de fraudes e resguardo jurídico.</p>

                    <h4 class="font-bold text-slate-800 mt-6 mb-2">7. Contato</h4>
                    <p class="mb-4">Para solicitações relacionadas à privacidade, entre em contato: <a href="mailto:privacidade@frotamaster.com" class="text-blue-600 hover:underline">privacidade@frotamaster.com</a></p>

                    <hr class="border-slate-200 my-8">

                    <h3 class="text-lg font-bold text-slate-900 mb-2">CONSENTIMENTO E CIÊNCIA FINAL</h3>
                    <p class="mb-2">Ao clicar em “Cadastrar e Acessar”, o Usuário declara estar plenamente ciente e de acordo com:</p>
                    <ul class="list-disc pl-5 space-y-1 mb-2">
                        <li>Os Termos de Uso e a Política de Privacidade,</li>
                        <li>A operação multiempresa da Plataforma e compartilhamento de infraestrutura,</li>
                        <li>A responsabilidade integral do Usuário Master pela gestão, permissões e ações dos usuários vinculados à sua Empresa.</li>
                    </ul>
                    <p class="font-medium text-slate-900">O uso contínuo da Plataforma configura aceitação integral e permanente.</p>

                </div>
                <div class="p-4 border-t bg-slate-50 flex justify-end">
                    <button @click="openModal = false" class="px-5 py-2.5 bg-slate-800 text-white font-medium rounded-lg hover:bg-slate-700 transition shadow-sm">
                        Entendi e Concordo
                    </button>
                </div>
            </div>
        </div>

    </div> <!-- Fechamento da DIV principal com x-data -->

    {{-- Script de Máscara --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && $.fn.mask) {
                var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
                $('#telefone_contato').mask(SPMaskBehavior, spOptions);
            }
        });
    </script>
</x-guest-layout>