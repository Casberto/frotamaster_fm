{{-- Bloco para exibir erros de validação --}}
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6" role="alert">
        <p class="font-bold">Atenção! Verifique os erros abaixo:</p>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    // Listas de opções para os selects
    $marcas = ['Agrale', 'Aston Martin', 'Audi', 'BMW', 'BYD', 'CAOA Chery', 'Chevrolet', 'Citroën', 'Dodge', 'Ferrari', 'Fiat', 'Ford', 'GWM', 'Honda', 'Hyundai', 'Jac', 'Jaguar', 'Jeep', 'Kia', 'Lamborghini', 'Land Rover', 'Lexus', 'Maserati', 'McLaren', 'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Nissan', 'Peugeot', 'Porsche', 'Ram', 'Renault', 'Rolls-Royce', 'Subaru', 'Suzuki', 'Toyota', 'Troller', 'Volkswagen', 'Volvo', 'Avelloz', 'Bajaj', 'Dafra', 'Haojue', 'Harley-Davidson', 'Kawasaki', 'KTM', 'Mottu', 'Royal Enfield', 'Shineray', 'Triumph', 'Yamaha', 'DAF', 'Iveco', 'MAN', 'Scania', 'Volvo Caminhões'];
    sort($marcas);
    $cores = ['Amarelo', 'Azul', 'Bege', 'Branco', 'Cinza', 'Dourado', 'Grená', 'Laranja', 'Marrom', 'Prata', 'Preto', 'Rosa', 'Roxo', 'Verde', 'Vermelho', 'Fantasia'];
    sort($cores);


    // Listas de opções capturadas das tabelas oficiais do DENATRAN
    $tipos = [
        '2' => 'Ciclomotor',
        '3' => 'Motoneta',
        '4' => 'Motocicleta',
        '5' => 'Triciclo',
        '6' => 'Automóvel',
        '7' => 'Micro-ônibus',
        '8' => 'Ônibus',
        '10' => 'Reboque',
        '11' => 'Semirreboque',
        '13' => 'Camioneta',
        '14' => 'Caminhão',
        '17' => 'Caminhão-trator',
        '18' => 'Trator de Rodas',
        '19' => 'Trator de Esteiras', // Adicionado por precaução, embora raro em frota urbana
        '20' => 'Trator Misto',
        '21' => 'Quadriciclo',
        '22' => 'Chassi Plataforma',
        '23' => 'Caminhonete',
        '24' => 'Carga', // Geralmente não é Tipo, mas em algumas tabelas antigas aparece. Mantendo só se necessário. Na tabela nova não vi "24-Carga" como tipo base, mas "Caminhonete" é 23 e "Utilitário" 25.
        '25' => 'Utilitário',
        '26' => 'Motor-casa'
    ];
    asort($tipos);

    $especies = [
        '1' => 'Passageiro',
        '2' => 'Carga',
        '3' => 'Misto',
        '4' => 'Competição',
        '5' => 'Tração',
        '6' => 'Especial',
        '7' => 'Coleção'
    ];
    asort($especies);

    // Códigos oficiais de Carroceria
    $carrocerias = [
        '000' => 'Não aplicável', // Usado na tabela como "Não se aplica"
        '999' => 'Nenhuma',
        '101' => 'Ambulância',
        '102' => 'Basculante',
        '104' => 'Bombeiro',
        '105' => 'Buggy',
        '106' => 'Cabine Dupla',
        '107' => 'Carroceria Aberta',
        '108' => 'Carroceria Fechada',
        '109' => 'Chassi Porta Contêiner',
        '110' => 'Conversível',
        '111' => 'Funeral',
        '112' => 'Furgão',
        '113' => 'Jipe',
        '115' => 'Limusine',
        '116' => 'Mecanismo Operacional',
        '118' => 'Prancha',
        '119' => 'Sidecar',
        '120' => 'Silo',
        '121' => 'Tanque',
        '122' => 'Trailler',
        '123' => 'Transporte de militar',
        '124' => 'Transporte de Presos',
        '125' => 'Transporte Recreativo',
        '126' => 'Transporte de Trabalhadores',
        '127' => 'Contêiner / Carroceria Aberta',
        '128' => 'Prancha Contêiner',
        '129' => 'Cabine Estendida',
        '130' => 'Trio Elétrico',
        '131' => 'Dolly',
        '132' => 'Intercambiável',
        '133' => 'Roll-on Roll-off',
        '134' => 'Carroceria Aberta / Cabine Dupla',
        '135' => 'Carroceria Aberta / Cabine Estendida',
        '136' => 'Carroceria Aberta / Cabine Suplementar',
        '137' => 'Carroceria Fechada / Cabine Dupla',
        '138' => 'Carroceria Fechada / Cabine Estendida',
        '139' => 'Carroceria Fechada / Cabine Suplementar',
        '140' => 'Carroceria Aberta / Intercambiável',
        '141' => 'Cabine Dupla / Inacabada',
        '142' => 'Mecanismo Operacional / Cabine Dupla',
        '143' => 'Transporte de Toras',
        '144' => 'Inacabada / Cabine Estendida',
        '145' => 'Carroceria Aberta / Mecanismo Operacional',
        '146' => 'Carroceria Fechada / Mecanismo Operacional',
        '147' => 'Tanque / Mecanismo Operacional',
        '148' => 'Prancha / Mecanismo Operacional',
        '149' => 'Carroceria Aberta / Mecanismo Operacional / Cabine Dupla',
        '150' => 'Carroceria Aberta / Mecanismo Operacional / Cabine Estendida',
        '151' => 'Carroceria Aberta / Mecanismo Operacional / Cabine Suplementar',
        '152' => 'Carroceria Fechada / Mecanismo Operacional / Cabine Dupla',
        '153' => 'Carroceria Fechada / Mecanismo Operacional / Cabine Estendida',
        '154' => 'Carroceria Fechada / Mecanismo Operacional / Cabine Suplementar',
        '155' => 'Tanque / Cabine Dupla',
        '156' => 'Tanque / Cabine Estendida',
        '157' => 'Tanque / Cabine Suplementar',
        '158' => 'Tanque / Mecanismo Operacional / Cabine Dupla',
        '159' => 'Tanque / Mecanismo Operacional / Cabine Estendida',
        '160' => 'Tanque / Mecanismo Operacional / Cabine Suplementar',
        '161' => 'Roll-on Roll-off / Cabine Dupla',
        '162' => 'Roll-on Roll-off / Cabine Estendida',
        '163' => 'Roll-on Roll-off / Cabine Suplementar',
        '164' => 'Basculante / Cabine Dupla',
        '165' => 'Basculante / Cabine Estendida',
        '166' => 'Basculante / Cabine Suplementar',
        '167' => 'Prancha / Cabine Dupla',
        '168' => 'Prancha / Cabine Estendida',
        '169' => 'Prancha / Cabine Suplementar',
        '171' => 'Prancha / Mecanismo Operacional / Cabine Estendida',
        '172' => 'Prancha / Mecanismo Operacional / Cabine Suplementar',
        '173' => 'Carroceria Aberta / Intercambiável / Cabine Dupla',
        '174' => 'Carroceria Aberta / Intercambiável / Cabine Estendida',
        '175' => 'Carroceria Aberta / Intercambiável / Cabine Suplementar',
        '176' => 'Carroceria Aberta / Cabine Tripla',
        '177' => 'Carroceria Fechada / Cabine Tripla',
        '178' => 'Comércio',
        '179' => 'Transporte de Granito',
        '180' => 'Silo / Basculante',
        '181' => 'Basculante / Mecanismo Operacional',
        '182' => 'Chassi Contêiner / Cabine Estendida',
        '183' => 'Mecanismo Operacional / Cabine Estendida',
        '184' => 'Silo / Cabine Estendida',
        '185' => 'Contêiner / Carroceria Aberta / Cabine Estendida',
        '186' => 'Prancha Contêiner / Cabine Estendida',
        '187' => 'Transporte de Toras / Cabine Estendida',
        '188' => 'Silo / Basculante / Cabine Estendida',
        '189' => 'Som',
        '190' => 'Transporte Escolar',
        '191' => 'Transporte de Valores',
        '192' => 'Transporte de Valores / Mecanismo Operacional',
        '193' => 'Tanque Produto Perigoso',
        '194' => 'Inacabada',
        '195' => 'Transporte de Granito / Cabine Estendida',
        '196' => 'Basculante / Mecanismo Operacional / Cabine Estendida',
        '197' => 'Chassi Contêiner / Cabine Dupla',
        '198' => 'Silo / Cabine Dupla',
        '199' => 'Contêiner / Carroceria Aberta / Cabine Dupla',
        '200' => 'Prancha Contêiner / Cabine Dupla',
        '201' => 'Transporte de Toras / Cabine Dupla',
        '202' => 'Transporte de Granito / Cabine Dupla',
        '203' => 'Silo / Basculante / Cabine Dupla',
        '204' => 'Basculante / Mecanismo Operacional / Cabine Dupla',
        '205' => 'Cabine Suplementar',
        '206' => 'Chassi Contêiner / Cabine Suplementar',
        '207' => 'Mecanismo Operacional / Cabine Suplementar',
        '208' => 'Silo / Cabine Suplementar',
        '209' => 'Contêiner / Carroceria Aberta / Cabine Suplementar',
        '210' => 'Prancha Contêiner / Cabine Suplementar',
        '211' => 'Transporte de Toras / Cabine Suplementar',
        '212' => 'Transporte de Granito / Cabine Suplementar',
        '213' => 'Silo / Basculante / Cabine Suplementar',
        '214' => 'Basculante / Mecanismo Operacional / Cabine Suplementar',
        '215' => 'Inacabada / Cabine Suplementar',
        '216' => 'Cabine Linear',
        '217' => 'Basculante / Cabine Linear',
        '218' => 'Carroceria Aberta / Cabine Linear',
        '219' => 'Carroceria Fechada / Cabine Linear',
        '220' => 'Chassi Contêiner / Cabine Linear',
        '221' => 'Mecanismo Operacional / Cabine Linear',
        '222' => 'Prancha / Cabine Linear',
        '223' => 'Silo / Cabine Linear',
        '224' => 'Tanque / Cabine Linear',
        '225' => 'Contêiner / Carroceria Aberta / Cabine Linear',
        '226' => 'Prancha Contêiner / Cabine Linear',
        '227' => 'Roll-on Roll-off / Cabine Linear',
        '228' => 'Transporte de Toras / Cabine Linear',
        '229' => 'Aberta / Intercambiável / Cabine Linear',
        '230' => 'Carroceria Aberta / Mecanismo Operacional / Cabine Linear',
        '231' => 'Carroceria Fechada / Mecanismo Operacional / Cabine Linear',
        '232' => 'Tanque / Mecanismo Operacional / Cabine Linear',
        '233' => 'Cabine Linear / Prancha / Mecanismo Operacional',
        '234' => 'Transporte de Granito / Cabine Linear',
        '235' => 'Silo / Basculante / Cabine Linear',
        '236' => 'Basculante / Mecanismo Operacional / Cabine Linear',
        '237' => 'Inacabada / Cabine Linear',
        '238' => 'Cabine Tripla',
        '239' => 'Mecanismo Operacional / Cabine Tripla',
        '240' => 'Inacabada / Cabine Tripla',
        '241' => 'Tanque Produto Perigoso / Cabine Estendida',
        '242' => 'Tanque Produto Perigoso / Cabine Dupla',
        '243' => 'Tanque Produto Perigoso / Cabine Suplementar',
        '244' => 'Tanque Produto Perigoso / Cabine Linear',
        '246' => 'Tanque Produto Perigoso / Mecanismo Operacional',
        '247' => 'Tanque Produto Perigoso / Mecanismo Operacional / Cabine Estendida',
        '248' => 'Tanque Produto Perigoso / Mecanismo Operacional / Cabine Dupla',
        '249' => 'Tanque Produto Perigoso / Mecanismo Operacional / Cabine Suplementar',
        '250' => 'Tanque Produto Perigoso / Mecanismo Operacional / Cabine Linear',
        '251' => 'Transporte de Toras / Mecanismo Operacional',
        '252' => 'Transporte de Toras / Mecanismo Operacional / Cabine Estendida',
        '253' => 'Transporte de Toras / Mecanismo Operacional / Cabine Dupla',
        '254' => 'Transporte de Toras / Mecanismo Operacional / Cabine Suplementar',
        '255' => 'Transporte de Toras / Mecanismo Operacional / Cabine Linear',
        '256' => 'Comboio',
        '257' => 'VTAV',
        '258' => 'VTAV / Cabine Estendida',
        '259' => 'VTAV / Cabine Linear',
        '260' => 'VTAV / Cabine Dupla',
        '261' => 'VTAV / Cabine Tripla',
        '262' => 'VTAV / Mecanismo Operacional',
        '263' => 'VTAV / Cabine Estendida / Mecanismo Operacional',
        '264' => 'VTAV / Cabine Linear / Mecanismo Operacional',
        '265' => 'VTAV / Cabine Dupla / Mecanismo Operacional',
        '266' => 'VTAV / Cabine Tripla / Mecanismo Operacional',
        '267' => 'VTAV / Trailler',
        '268' => 'Transporte de Cilindros Interligados',
        '269' => 'Comboio / Cabine Estendida',
        '270' => 'Comércio / Cabine Dupla',
        '271' => 'Comércio / Cabine Estendida',
        '272' => 'Atenuador de Impacto',
        '273' => 'Basculante / Cabine Estendida Linear',
        '274' => 'Carroceria Aberta / Cabine Estendida Linear',
        '275' => 'Carroceria Fechada / Cabine Estendida Linear',
        '276' => 'Chassi Contêiner / Cabine Estendida Linear',
        '277' => 'Mecanismo Operacional / Cabine Estendida Linear',
        '278' => 'Prancha / Cabine Estendida Linear',
        '279' => 'Silo / Cabine Estendida Linear',
        '280' => 'Tanque / Cabine Estendida Linear',
        '281' => 'Contêiner / Carroceria Aberta / Cabine Estendida Linear',
        '282' => 'Prancha Contêiner / Cabine Estendida Linear',
        '283' => 'Roll-on Roll-off / Cabine Estendida Linear',
        '284' => 'Transporte de Toras / Cabine Estendida Linear',
        '285' => 'Aberta / Intercambiável / Cabine Estendida Linear',
        '286' => 'Carroceria Aberta / Mecanismo Operacional / Cabine Estendida Linear',
        '287' => 'Carroceria Fechada / Mecanismo Operacional / Cabine Estendida Linear',
        '288' => 'Tanque / Mecanismo Operacional / Cabine Estendida Linear',
        '289' => 'Prancha / Mecanismo Operacional / Cabine Estendida Linear',
        '290' => 'Transporte de Granito / Cabine Estendida Linear',
        '291' => 'Silo / Basculante / Cabine Estendida Linear',
        '292' => 'Basculante / Mecanismo Operacional / Cabine Estendida Linear',
        '293' => 'Tanque Produto Perigoso / Cabine Estendida Linear',
        '294' => 'Tanque Produto Perigoso / Mecanismo Operacional / Cabine Estendida Linear',
        '295' => 'Cabine Estendida Linear',
        '296' => 'Transporte de Toras / Mecanismo Operacional / Cabine Estendida Linear',
        '297' => 'Comboio / Cabine Dupla',
        '298' => 'Comboio / Cabine Suplementar',
        '299' => 'Comboio / Cabine Linear',
        '300' => 'Comboio / Cabine Estendida Linear',
        '301' => 'VTAV / Cabine Estendida Linear',
        '302' => 'VTAV / Cabine Estendida Linear / Mecanismo Operacional',
        '303' => 'Inacabada / Cabine Estendida Linear',
        '304' => 'Comércio / Cabine Linear',
        '305' => 'Comércio / Estendida Linear',
        '306' => 'Mecanismo Operacional / Roll-on Roll-off'
    ];
    asort($carrocerias);


    // Definição das Regras de Dependência Oficiais
    $regras = [
        '2' => [ // Ciclomotor
            'especies' => ['1', '2'],
            'carrocerias' => [
                '1' => ['999'], // Passageiro -> Nenhuma
                '2' => ['999'], // Carga -> Nenhuma
            ]
        ],
        '3' => [ // Motoneta
            'especies' => ['1', '2'],
            'carrocerias' => [
                '1' => ['999'],
                '2' => ['999'],
            ]
        ],
        '4' => [ // Motocicleta
            'especies' => ['1', '2', '6'],
            'carrocerias' => [
                '1' => ['999', '119'], // Pass: Nenhuma, Sidecar
                '2' => ['999', '119'], // Carga: Nenhuma, Sidecar
                '6' => ['101', '104']  // Esp: Amb, Bomb
            ]
        ],
        '5' => [ // Triciclo
            'especies' => ['1', '2', '6'],
            'carrocerias' => [
                '1' => ['999', '108'], // Pass: Nenhuma, Fechada
                '2' => ['999', '102', '107', '108'], // Carga: Nenhuma, Basc, Aberta, Fechada
                '6' => ['101', '104'] // Esp: Amb, Bomb
            ]
        ],
        '6' => [ // Automóvel
            'especies' => ['1', '6'],
            'carrocerias' => [
                '1' => ['999', '105', '110', '115'], // Pass: Nenhuma, Buggy, Conversivel, Limusine
                '6' => ['101', '104', '111', '115', '124', '178'] // Esp: Amb, Bomb, Funer, Limus, Presos, Comércio
            ]
        ],
        '7' => [ // Micro-ônibus
            'especies' => ['1', '6'],
            'carrocerias' => [
                '1' => ['999', '190'], // Pass: Nenhuma, Escolar
                '6' => ['101', '104', '111', '124', '125', '126', '178', '191', '192']
            ]
        ],
        '8' => [ // Ônibus
            'especies' => ['1', '6'],
            'carrocerias' => [
                '1' => ['999', '190'],
                '6' => ['101', '104', '111', '124', '125', '126', '178', '191', '192']
            ]
        ],
        '10' => [ // Reboque
            'especies' => ['1', '2', '6'],
            'carrocerias' => [
                '1' => ['123', '124', '125', '126'],
                '2' => ['102','107','108','109','116','118','120','121', '127','128','132','133','143','145','146','179','180','181','193','257','262','268'],
                '6' => ['101','104','111','122','130','131','191','267','272']
            ]
        ],
        '11' => [ // Semirreboque
            'especies' => ['1', '2', '6'],
            'carrocerias' => [
                '1' => ['123', '124', '125', '126'],
                '2' => ['102','107','108','109','116','118','120','121', '127','128','132','133','143','145','146','179','180','181','193','251','257','262','268'],
                '6' => ['101','104','111','122','130','131','191','267']
            ]
        ],
        '13' => [ // Camioneta
            'especies' => ['3', '6'],
            'carrocerias' => [
                '3' => ['999', '190'],
                '6' => ['101','104','111','115','124','178','189']
            ]
        ],
        '14' => [ // Caminhão
            'especies' => ['2', '6'],
            'carrocerias' => [
                '2' => [
                    '102','107','108','109','112','116','118','120','121','127','128','133','135','138','140','143','144','145','146','147','148','150','153','156','159','162','165','168','171','174','179','180','181','182','183','184','185','186','187','188','193','194','195','196','241','246','247','251','252','256','257','258','262','263','269','306'
                ],
                '6' => [
                    '101','104','111','115','123','124','125','126','130','134','136','137','139','141','142','149','151','152','154','155','157','158','160','161','163','164','166','167','169','170','172','173','175','176','177','178','191','192','197','198','199','200','201','202','203','204','206','207','208','209','210','211','212','213','214','215','217','218','219','220','221','222','223','224','225','226','227','228','229','230','231','232','233','234','235','236','237','239','240','242','243','244','248','249','250','253','254','255','259','260','261','264','265','266','273','274','275','276','277','278','279','280','281','282','283','284','285','286','287','288','289','290','291','292','293','294','296','297','298','299','300','301','302','303','304','305'
                ]
            ]
        ],
        '17' => [ // Caminhão-trator
            'especies' => ['5'], // Tração
            'carrocerias' => [
                 '5' => ['999','104','106','116','129','142','183','191','205','216','221','238','239','277','295']
            ]
        ],
        '18' => [ // Tr Rodas
             'especies' => ['5'],
             'carrocerias' => ['5' => ['999']]
        ],
        '19' => [ // Tr Esteiras
             'especies' => ['5'],
             'carrocerias' => ['5' => ['999']]
        ],
        '20' => [ // Tr Misto
             'especies' => ['5'],
             'carrocerias' => ['5' => ['999']]
        ],
        '21' => [ // Quadriciclo
             'especies' => ['1', '2'],
             'carrocerias' => ['1' => ['999'], '2' => ['999']]
        ],
        '22' => [ // Chassi Plataforma
             'especies' => ['1', '6'],
             'carrocerias' => ['1' => ['000'], '6' => ['000']] // Não se aplica
        ],
        '23' => [ // Caminhonete
             'especies' => ['2', '6'],
             'carrocerias' => [
                '2' => [
                    '102','107','108','112','116','121','132','135','138','140','144','145','146','150','153','156','165','174','181','183','193','196','246','256','258','262','263'
                ],
                '6' => [
                    '101','104','111','115','123','124','125','126','130','134','136','137','139','141','142','149','151','152','154','155','164','173','175','176','177','178','189','191','207','215','239','240','245','260','261','265','270','271'
                ]
            ]
        ],
        '25' => [ // Utilitário
             'especies' => ['3', '6'],
             'carrocerias' => [
                 '3' => ['999', '107', '108', '113'],
                 '6' => ['101', '104', '111', '115', '124', '178']
             ]
        ],
        '26' => [ // Motor-casa
             'especies' => ['6'],
             'carrocerias' => ['6' => ['108']]
        ]
    ];
@endphp


<div class="space-y-6" x-data="dependenciaVeiculos()">
    
    {{-- TAB 1: DADOS GERAIS --}}
    <div x-show="tab === 'geral' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Identificação Principal
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                {{-- Placa --}}
                <div class="md:col-span-3">
                    <label for="vei_placa" class="block font-medium text-sm text-gray-700">Placa *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text" name="vei_placa" id="vei_placa" class="block w-full uppercase border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-3" value="{{ old('vei_placa', optional($veiculo)->vei_placa) }}" required maxlength="8" placeholder="ABC1D23">
                    </div>
                </div>

                {{-- Fabricante --}}
                <div class="md:col-span-3">
                    <label for="vei_fabricante" class="block font-medium text-sm text-gray-700">Fabricante *</label>
                    <input list="marcas-list" name="vei_fabricante" id="vei_fabricante" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_fabricante', optional($veiculo)->vei_fabricante) }}" required placeholder="Selecione ou digite">
                    <datalist id="marcas-list">
                        @foreach($marcas as $marcaOption)
                            <option value="{{ $marcaOption }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Modelo --}}
                <div class="md:col-span-6">
                    <label for="vei_modelo" class="block font-medium text-sm text-gray-700">Modelo *</label>
                    <input type="text" name="vei_modelo" id="vei_modelo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_modelo', optional($veiculo)->vei_modelo) }}" required placeholder="Ex: Gol 1.6 MSI">
                </div>

                {{-- Ano Fab/Mod --}}
                <div class="md:col-span-3">
                    <label for="vei_ano_fab" class="block font-medium text-sm text-gray-700">Ano Fabricação *</label>
                    <input type="number" name="vei_ano_fab" id="vei_ano_fab" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_ano_fab', optional($veiculo)->vei_ano_fab) }}" required min="1940" max="{{ date('Y') + 1 }}">
                </div>
                <div class="md:col-span-3">
                    <label for="vei_ano_mod" class="block font-medium text-sm text-gray-700">Ano Modelo *</label>
                    <input type="number" name="vei_ano_mod" id="vei_ano_mod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_ano_mod', optional($veiculo)->vei_ano_mod) }}" required min="1940" max="{{ date('Y') + 1 }}">
                </div>

                {{-- Cor --}}
                <div class="md:col-span-3">
                     <label for="vei_cor_predominante" class="block font-medium text-sm text-gray-700">Cor Predominante *</label>
                    <select name="vei_cor_predominante" id="vei_cor_predominante" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Selecione...</option>
                        @foreach($cores as $corOption)
                            <option value="{{ $corOption }}" @selected(old('vei_cor_predominante', optional($veiculo)->vei_cor_predominante) == $corOption)>{{ $corOption }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="md:col-span-3">
                    <label for="vei_status" class="block font-medium text-sm text-gray-700">Status *</label>
                    <select name="vei_status" id="vei_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('vei_status', optional($veiculo)->vei_status ?? 1) == 1)>Ativo</option>
                        <option value="2" @selected(old('vei_status', optional($veiculo)->vei_status) == 2)>Inativo</option>
                        <option value="3" @selected(old('vei_status', optional($veiculo)->vei_status) == 3)>Em Manutenção</option>
                        <option value="4" @selected(old('vei_status', optional($veiculo)->vei_status) == 4)>Vendido</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Classificação CONTRAN
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- TIPO -->
                <div class="md:col-span-4">
                    <label for="vei_tipo" class="block font-medium text-sm text-gray-700">Tipo *</label>
                    <select name="vei_tipo" id="vei_tipo" 
                            x-model="tipoSelecionado"
                            @change="atualizarEspecies()"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            required>
                        <option value="">Selecione...</option>
                        @foreach($tipos as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- ESPÉCIE -->
                <div class="md:col-span-4">
                    <label for="vei_especie" class="block font-medium text-sm text-gray-700">Espécie *</label>
                    <select name="vei_especie" id="vei_especie" 
                            x-model="especieSelecionada"
                            @change="atualizarCarrocerias()"
                            :disabled="!tipoSelecionado"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500" 
                            required>
                        <option value="">Selecione...</option>
                        <!-- As opções são geradas dinamicamente via Alpine mas mantemos backend como fallback/initial data se necessário, mas aqui vamos usar x-for ou lógica de show -->
                        <template x-for="(nome, id) in especiesFiltradas" :key="id">
                            <option :value="id" x-text="nome" :selected="id == especieSelecionada"></option>
                        </template>
                    </select>
                </div>

                <!-- CARROCERIA -->
                <div class="md:col-span-4">
                    <label for="vei_carroceria" class="block font-medium text-sm text-gray-700">Carroceria *</label>
                    <select name="vei_carroceria" id="vei_carroceria" 
                            x-model="carroceriaSelecionada"
                            :disabled="!especieSelecionada"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500" 
                            required>
                        <option value="">Selecione...</option>
                        <template x-for="(nome, id) in carroceriasFiltradas" :key="id">
                            <option :value="id" x-text="nome" :selected="id == carroceriaSelecionada"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js para Controle de Dependências -->
    <script>
        function dependenciaVeiculos() {
            return {
                // Dados Mestres (passados do PHP)
                todasEspecies: @json($especies),
                todasCarrocerias: @json($carrocerias),
                regras: @json($regras),

                // Estados
                tipoSelecionado: '{{ old('vei_tipo', optional($veiculo)->vei_tipo) }}',
                especieSelecionada: '{{ old('vei_especie', optional($veiculo)->vei_especie) }}',
                carroceriaSelecionada: '{{ old('vei_carroceria', optional($veiculo)->vei_carroceria) }}',

                // Listas Filtradas
                especiesFiltradas: {},
                carroceriasFiltradas: {},

                init() {
                    // Inicializar listas baseadas nos valores iniciais (Edição ou Old Input)
                    if (this.tipoSelecionado) {
                        this.atualizarEspecies(false);
                    }
                    if (this.tipoSelecionado && this.especieSelecionada) {
                        this.atualizarCarrocerias(false);
                    }
                },

                atualizarEspecies(resetChild = true) {
                    if (resetChild) {
                        this.especieSelecionada = '';
                        this.carroceriaSelecionada = '';
                        this.carroceriasFiltradas = {};
                    }

                    // Se não tiver regra para o tipo, libera tudo ou bloqueia?
                    // Vamos assumir que se não tem regra definida, mostra tudo (fallback) ou vazio.
                    // Pela regra de negócio: bloqueia se não selecionar.
                    
                    if (!this.tipoSelecionado || !this.regras[this.tipoSelecionado]) {
                        this.especiesFiltradas = {};
                        return;
                    }

                    const especiesPermitidasIds = this.regras[this.tipoSelecionado].especies || [];
                    
                    // Filtrar o objeto todasEspecies
                    this.especiesFiltradas = Object.fromEntries(
                        Object.entries(this.todasEspecies).filter(([id]) => especiesPermitidasIds.includes(String(id)))
                    );
                },

                atualizarCarrocerias(resetChild = true) {
                    if (resetChild) {
                        this.carroceriaSelecionada = '';
                    }

                    if (!this.tipoSelecionado || !this.especieSelecionada || !this.regras[this.tipoSelecionado]) {
                        this.carroceriasFiltradas = {};
                        return;
                    }

                    const regraTipo = this.regras[this.tipoSelecionado];
                    let carrosPermitidosIds = [];

                    // Verificar regras específicas por espécie
                    if (regraTipo.carrocerias) {
                        // Tenta achar regra específica para a espécie
                        if (regraTipo.carrocerias[this.especieSelecionada]) {
                            carrosPermitidosIds = regraTipo.carrocerias[this.especieSelecionada];
                        } 
                        // Regra curinga '*' (aplicável a qualquer espécie desse tipo)
                        else if (regraTipo.carrocerias['*']) {
                            carrosPermitidosIds = regraTipo.carrocerias['*'];
                        }
                    }

                    // Filtrar o objeto todasCarrocerias
                    this.carroceriasFiltradas = Object.fromEntries(
                        Object.entries(this.todasCarrocerias).filter(([id]) => carrosPermitidosIds.includes(String(id)))
                    );
                }
            }
        }
    </script>

    {{-- TAB 2: DETALHES TÉCNICOS --}}
    <div x-show="tab === 'tecnico' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Motorização e Combustível
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-4">
                    <label for="vei_combustivel" class="block font-medium text-sm text-gray-700">Combustível *</label>
                    <select name="vei_combustivel" id="vei_combustivel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="1" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 1)>Gasolina</option>
                        <option value="2" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 2)>Álcool/Etanol</option>
                        <option value="3" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 3)>Diesel</option>
                        <option value="6" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 6)>Flex (Gasolina/Álcool)</option>
                        <option value="4" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 4)>GNV</option>
                        <option value="5" @selected(old('vei_combustivel', optional($veiculo)->vei_combustivel) == 5)>Elétrico</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label for="vei_potencia" class="block font-medium text-sm text-gray-700">Potência (CV)</label>
                    <input type="text" name="vei_potencia" id="vei_potencia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_potencia', optional($veiculo)->vei_potencia) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_cilindradas" class="block font-medium text-sm text-gray-700">Cilindradas (CC)</label>
                    <input type="text" name="vei_cilindradas" id="vei_cilindradas" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_cilindradas', optional($veiculo)->vei_cilindradas) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_num_motor" class="block font-medium text-sm text-gray-700">Número do Motor</label>
                    <input type="text" name="vei_num_motor" id="vei_num_motor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_num_motor', optional($veiculo)->vei_num_motor) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_cap_tanque" class="block font-medium text-sm text-gray-700">Capacidade Tanque/Bateria</label>
                    <input type="text" name="vei_cap_tanque" id="vei_cap_tanque" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Ex: 50L ou 75kWh" value="{{ old('vei_cap_tanque', optional($veiculo)->vei_cap_tanque) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Quilometragem
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label for="vei_km_inicial" class="block font-medium text-sm text-gray-700">KM Inicial *</label>
                    <input type="number" name="vei_km_inicial" id="vei_km_inicial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_km_inicial', optional($veiculo)->vei_km_inicial ?? '0') }}" required max="9999999">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_km_atual" class="block font-medium text-sm text-gray-700">KM Atual *</label>
                    <input type="number" name="vei_km_atual" id="vei_km_atual" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_km_atual', optional($veiculo)->vei_km_atual ?? '0') }}" required max="9999999">
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: DOCUMENTAÇÃO E PESOS --}}
    <div x-show="tab === 'docs' || mobile" class="space-y-6 animate-fade-in-up mobile-stacked-force" style="display: none;">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Documentação
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-4">
                    <label for="vei_renavam" class="block font-medium text-sm text-gray-700">Renavam</label>
                    <input type="text" name="vei_renavam" id="vei_renavam" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_renavam', optional($veiculo)->vei_renavam) }}" maxlength="11">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_chassi" class="block font-medium text-sm text-gray-700">Chassi (VIN)</label>
                    <input type="text" name="vei_chassi" id="vei_chassi" class="mt-1 block w-full uppercase border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_chassi', optional($veiculo)->vei_chassi) }}" maxlength="17">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_crv" class="block font-medium text-sm text-gray-700">Nº CRV</label>
                    <input type="text" name="vei_crv" id="vei_crv" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_crv', optional($veiculo)->vei_crv) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_data_licenciamento" class="block font-medium text-sm text-gray-700">Data Último Licenciamento</label>
                    <input type="date" name="vei_data_licenciamento" id="vei_data_licenciamento" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_licenciamento', optional(optional($veiculo)->vei_data_licenciamento)->format('Y-m-d')) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_antt" class="block font-medium text-sm text-gray-700">Cód. ANTT</label>
                    <input type="text" name="vei_antt" id="vei_antt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_antt', optional($veiculo)->vei_antt) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                Pesos e Capacidades
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4">
                    <label for="vei_tara" class="block font-medium text-sm text-gray-700">Tara (kg)</label>
                    <input type="number" name="vei_tara" id="vei_tara" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_tara', optional($veiculo)->vei_tara) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_lotacao" class="block font-medium text-sm text-gray-700">Lotação (kg)</label>
                    <input type="number" name="vei_lotacao" id="vei_lotacao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_lotacao', optional($veiculo)->vei_lotacao) }}">
                </div>
                <div class="md:col-span-4">
                    <label for="vei_pbt" class="block font-medium text-sm text-gray-700">PBT (kg)</label>
                    <input type="number" name="vei_pbt" id="vei_pbt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_pbt', optional($veiculo)->vei_pbt) }}">
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Histórico de Aquisição
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">
                <div class="md:col-span-6">
                    <label for="vei_data_aquisicao" class="block font-medium text-sm text-gray-700">Data de Aquisição *</label>
                    <input type="date" name="vei_data_aquisicao" id="vei_data_aquisicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_aquisicao', optional(optional($veiculo)->vei_data_aquisicao)->format('Y-m-d')) }}" required>
                </div>
                 <div class="md:col-span-6">
                    <label for="vei_valor_aquisicao" class="block font-medium text-sm text-gray-700">Valor de Aquisição</label>
                    <input type="number" step="0.01" name="vei_valor_aquisicao" id="vei_valor_aquisicao" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_valor_aquisicao', optional($veiculo)->vei_valor_aquisicao) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_data_venda" class="block font-medium text-sm text-gray-700">Data da Venda</label>
                    <input type="date" name="vei_data_venda" id="vei_data_venda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_data_venda', optional(optional($veiculo)->vei_data_venda)->format('Y-m-d')) }}">
                </div>
                <div class="md:col-span-6">
                    <label for="vei_valor_venda" class="block font-medium text-sm text-gray-700">Valor de Venda</label>
                    <input type="number" step="0.01" name="vei_valor_venda" id="vei_valor_venda" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('vei_valor_venda', optional($veiculo)->vei_valor_venda) }}">
                </div>
                <div class="md:col-span-12">
                    <label for="vei_obs" class="block font-medium text-sm text-gray-700">Observações</label>
                    <textarea name="vei_obs" id="vei_obs" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('vei_obs', optional($veiculo)->vei_obs) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Campo oculto para o segmento --}}
    <input type="hidden" name="vei_segmento" value="1">
</div>
