<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Contrato {{$matricula->aluno->nome}}</title>
</head>
<style>
    table.report-container {
    page-break-after:always;
    }
    thead.report-header {
        display:table-header-group;
    }
    tfoot.report-footer {
        display:table-footer-group;
    } 

    .container img {
        max-width:200px;
        max-height:150px;
        width: auto;
        height: auto;
    }
</style>

<body>
    <table class="report-container">
        {{-- Cabeçalho repete em todas as páginas --}}
        <thead class="report-header">
            <tr>
              {{--  <td class="report-header-cell" width="15%">
                  <div class="header-info" align="left">
                        <img src="/vendor/adminlte/dist/img/logo.png" width="70%" alt="logo">
                  </div>
               </td> --}}
               <td class="report-header-cell" align="center">
                @include('secretaria.paginas._partials.cabecalho_redeeduca')
               </td>
            </tr>
        </thead>
        <tbody class="report-content">
            <tr>
                <td colspan=2>
                    <br>
                    <center><strong>CONTRATO DE PRESTAÇÃO DE SERVIÇOS EDUCACIONAIS - ANO LETIVO {{$matricula->turma->tipoTurma->anoLetivo->ano}}</strong></center>                    
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br><br>
                    <strong>QUADRO 01: CONTRATANTE (RESPONSÁVEL LEGAL)</strong>
                    <br>
                    Nome: {{$matricula->responsavel->nome}}  &nbsp&nbsp&nbsp CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->responsavel->cpf)}} &nbsp&nbsp&nbsp Doc. Identidade: {{$matricula->responsavel->tipoDocIdentidade->tipo_doc_identidade ?? ''}}-{{$matricula->responsavel->doc_identidade}} 
                    <br>
                    Endereço: {{$matricula->responsavel->endereco->endereco ?? ''}} - {{$matricula->responsavel->endereco->complemento ?? ''}} &nbsp&nbsp&nbsp Nº {{$matricula->responsavel->endereco->numero ?? ''}}
                    <br>
                    Bairro: {{$matricula->responsavel->endereco->bairro ?? ''}} &nbsp&nbsp&nbspCidade: {{$matricula->responsavel->endereco->cidade->cidade ?? ''}}/{{$matricula->responsavel->endereco->cidade->estado->sigla ?? ''}}    
                    &nbsp&nbsp&nbsp CEP: 
                    @if (isset($matricula->responsavel->endereco->cep)) 
                        {{mascaraCEP('##.###-###', $matricula->responsavel->endereco->cep) ?? ''}}
                    @endif
                    <br>
                    Fones: {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_1)}}  e {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_2)}}   &nbsp&nbsp&nbsp E-mail: {{$matricula->responsavel->email_1}}
                 
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br>
                    <strong>QUADRO 02: ALUNO(A)</strong>
                    <br>
                    Nome: {{$matricula->aluno->nome}}   &nbsp&nbsp&nbsp Série/Ano: {{$matricula->turma->nome_turma}} - {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino ?? ''}} - {{$matricula->turma->tipoTurma->anoLetivo->ano ?? ''}}
                    <br>
                     Data de Nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}} &nbsp&nbsp&nbsp CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->aluno->cpf)}}    &nbsp&nbsp&nbsp Fone: {{mascaraTelefone("(##) #####-####",$matricula->aluno->telefone_1)}}
                    
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br>
                    <strong>QUADRO 03: CONTRATADA</strong>
                    <br>
                    {{$unidadeEnsino->razao_social}}, inscrita no CNPJ sob o n° {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}, Matriz, sediada à {{$unidadeEnsino->endereco}}.
                    
                </td>
            </tr>            
            <tr>
                <td colspan=2>
                    <br>
                    <strong>QUADRO 04: DESCRIÇÃO DO INVESTIMENTO</strong>
                    <br><br>

                    <strong>4.1 - MATRÍCULA</strong>
                    R$ {{number_format($matricula->valor_matricula, 2, ',', '.')}}      &nbsp&nbsp&nbsp PAGTO: 
                    @if (isset($matricula->data_pagto_matricula))
                         {{date('d/m/Y', strtotime($matricula->data_pagto_matricula))}}
                    @endif
                         &nbsp&nbsp&nbsp Forma de Pagto: {{$matricula->formaPagamentoMatricula->forma_pagamento ?? ''}}
                    <br><br>

                    <strong>4.2 -PARCELAS (CURSO)</strong>
                    VLR CURSO: R$ {{number_format($matricula->turma->tipoTurma->valor_curso, 2, ',', '.') ?? ''}}
                    @if (isset($matricula->valor_desconto) && $matricula->valor_desconto > 0)
                        &nbsp&nbsp&nbsp VLR DESCONTO: R$ {{number_format($matricula->valor_desconto, 2, ',', '.') ?? ''}}     
                    @endif
                    <br>
                    QTD: {{$matricula->qt_parcelas_curso}} PARCELA(S) - VLR UNITÁRIO: R$ 
                    @if ($matricula->qt_parcelas_curso > 0)
                        {{number_format(($matricula->turma->tipoTurma->valor_curso-$matricula->valor_desconto)/$matricula->qt_parcelas_curso, 2, ',', '.') ?? ''}} 
                    @endif 
                    &nbsp&nbsp&nbsp Forma de pagto: {{$matricula->formaPagamentoCurso->forma_pagamento ?? ''}}
                    <br>
                    1° VCTO: {{date('d/m/Y', strtotime($matricula->data_venc_parcela_um))}} 
                    @if ($matricula->qt_parcelas_curso > 1)
                        e demais nos meses subsequentes.
                    @endif    
                    <br>
                    <strong>VLR TOTAL: R$ {{number_format($matricula->turma->tipoTurma->valor_curso - $matricula->valor_desconto, 2, ',', '.') ?? ''}}</strong>
                    <br><br>

                    <strong>4.3 - MATERIAL DIDÁTICO SISTEMATIZADO DOM BOSCO:</strong>
                    <br>                    
                    Valor entrada: R$ {{number_format($matricula->valor_entrada_mat_didatico, 2, ',', '.') ?? ''}}
                    &nbsp&nbsp&nbsp Forma de Pagto Entrada: {{$matricula->formaPagamentoEntradaMaterialDidatico->forma_pagamento ?? ''}} &nbsp&nbsp&nbsp 1º Pagto: {{date('d/m/Y', strtotime($matricula->data_pagto_mat_didatico))}}
                    <br>
                    QTD PARCELA(S): {{$matricula->qt_parcelas_mat_didatico}} - VLR UNITÁRIO: R$ 
                    @if ($matricula->qt_parcelas_mat_didatico > 0)
                        {{number_format($matricula->valor_material_didatico/$matricula->qt_parcelas_mat_didatico, 2, ',', '.') ?? ''}} 
                    @endif                    
                    &nbsp&nbsp&nbsp Forma de Pagto Parcelas: {{$matricula->formaPagamentoMaterialDidatico->forma_pagamento ?? ''}} 
                    <br>
                    <strong>VLR TOTAL MATERIAL DIDÁTICO: R$ {{number_format($matricula->valor_entrada_mat_didatico+$matricula->valor_material_didatico, 2, ',', '.') ?? ''}}</strong>                     
                </td>
            </tr>
            <?php
            $totalContratosExtras = 0;        
            ?>
            {{-- CONTRATOS ATIVIDADES EXTRACURRICULARES --}}
            @if (count($contratosExtraCurriculares) > 0)

                
                @foreach ($contratosExtraCurriculares as $index => $contratoExtraCurricular)

                    {{-- Somando valores dos contratos extracurriculares --}}
                    <?php
                        $totalContratosExtras += ($contratoExtraCurricular->valor_curso+$contratoExtraCurricular->valor_material)
                    ?>
                    
                    {{-- tratando quantidade de parcelas --}}
                    @if ($contratoExtraCurricular->quantidade_parcelas == 0 or $contratoExtraCurricular->quantidade_parcelas == '')
                        <?php
                            $quantidade_parcelas = 1;    
                        ?>
                    @else
                        <?php
                            $quantidade_parcelas = $contratoExtraCurricular->quantidade_parcelas;    
                        ?>
                    @endif
                    <tr>
                        
                        <td colspan="2">
                            <hr>
                            <strong>{{$index+5}} - {{mb_strToUpper($contratoExtraCurricular->titulo_contrato)}}:</strong>
                            <br>
                            {{$index+5}}.1 - <strong>PARCELAS (CURSO)</strong>
                            <br>
                            QTD: {{$contratoExtraCurricular->quantidade_parcelas}} PARCELAS - VLR UNITÁRIO: 
                            R$ {{number_format(($contratoExtraCurricular->valor_curso + $contratoExtraCurricular->valor_material)/$quantidade_parcelas, 2, ',', '.') ?? ''}}
                            Forma de Pagto: {{$contratoExtraCurricular->forma_pagto_curso}}
                            <br>
                            1° VCTO: {{$contratoExtraCurricular->data_venc_parcela_um}} e demais nos meses subsequentes.
                            <br>
                            {{$index+5}}.2 - <strong>PARCELAS (MATERIAL)</strong>
                            <br>
                            {{-- VLR R$: {{$contratoExtraCurricular->valor_material}} --}}
                            Forma de Pagto: {{$contratoExtraCurricular->forma_pagto_material}}
                        </td>
                    </tr>
                @endforeach
            @endif

            <tr>
                <td colspan="2">
                    <br>
                    @if(strlen($matricula->obs_matricula) > 0)
                        <strong>Informações complementares:</strong> {{$matricula->obs_matricula}}
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <hr>
                    <center>
                        <h4>VLR TOTAL: R$ {{number_format(($matricula->valor_matricula)+($matricula->turma->tipoTurma->valor_curso)-($matricula->valor_desconto)+($matricula->valor_entrada_mat_didatico)+($matricula->valor_material_didatico)+($totalContratosExtras), 2, ',', '.')}}</h4>
                    </center>
                </td>
            </tr>

            <tr>
                <td colspan=2>                                        
                    {{-- <center><b><u><br><br><b>Cláusula PRIMEIRA – DO OBJETO DO CONTRATO</u></b></center> --}}
                    <p align="justify">O objeto deste contrato é a prestação de serviços educacionais e compra do material didático selecionados nas opções descritas no quadro 04.</p>
                    <p align="justify">Pelo presente instrumento particular de contrato de Prestação de Serviços Educacionais, a “Escola” devidamente inscrita no CNPJ sob nº: {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}, entidade mantenedora do {{$unidadeEnsino->nome_fantasia}}, com sede à {{$unidadeEnsino->endereco}}, doravante denominada “{{$unidadeEnsino->razao_social}}”, neste ato representado pelo representante legal, abaixo assinado e o(a) Aluno(a) neste ato representado(a) por seu(sua) responsável legal/financeiro, a ser denominado(a) “Contratante”, qualificado(a)s no “Requerimento de Matrícula”, têm entre si justo e contratado o seguinte:</p>

                    <p align="justify"><b>Cláusula 1ª</b></b> - A Escola tem sua proposta educacional orientada para o atendimento da Educação Infantil, Ensino Fundamental e Ensino Médio, todos regulamentados, respectivamente, nos artigos 29, 32 e 35, da LDB 9394/96.                    
                        <br>Parágrafo 1º. A Escola se obriga a ministrar ensino através de aulas e demais atividades escolares, devendo o plano de estudos, programas, currículo e calendário estar em conformidade com o disposto na legislação em vigor e de acordo com seu Plano Escolar no período de janeiro a dezembro de 2021. 
                        <br>Parágrafo 2º. - O Contratante está ciente e concorda expressamente que em caso de decretação de estado de calamidade pública, reconhecido pelos órgãos governamentais, como de qualquer outra situação excepcional que resulte em suspensão das aulas e atividades escolares de forma presencial, poderá a Escola disponibilizar a sua metodologia de ensino de maneira remota, através de recursos tecnológicos em substituição às aulas presenciais, sob supervisão da direção e coordenação escolar. As aulas poderão ser síncronas (em tempo real) ou assíncronas (sem interação em tempo real), respeitando os conteúdos programados, conforme o Plano Escolar.
                        <br><br><b>Cláusula 2ª</b> - As aulas e atividades serão ministradas nas salas de aula ou locais indicados pela Escola, tendo em vista a natureza do conteúdo e da técnica pedagógica que se fizerem necessários.
                        <br>Parágrafo único: As atividades extras que forem oferecidas pela Escola e de interesse do Contratante, e que estão excluídas do objeto deste contrato, serão à parte, em aditivo contratual.
                        <br><br><b>Cláusula 3ª</b> - A configuração formal do ato de matrícula se procede pelo preenchimento do Requerimento de Matrícula, que desde já fica fazendo parte integrante deste contrato.
                        <br>Parágrafo 1º - O Requerimento de Matrícula somente será encaminhado para exame e deferimento pelo diretor após certificação pela tesouraria de que o Contratante esteja quite com suas obrigações financeiras decorrentes de prestações anteriores e as previstas para pagamento no ato de matrícula.
                        <br>Parágrafo 2º - Havendo pendências financeiras de anuidades anteriores junto à instituição de ensino e/ou não quitação da primeira parcela para pagamento no ato da matrícula, o requerimento de matrícula, reserva de vaga e a efetivação do presente contrato para o ano letivo de 2.021 não serão formalizados e validados. A contrata poderá a qualquer realizar cobranças judiciais de pendências financeiras de anos anteriores.  
                        <br><br><b>Cláusula 4ª</b> - São de inteira responsabilidade da Escola o planejamento e a prestação dos serviços de ensino, no que se refere a datas e horários das atividades, fixação de carga horária, designação de professores, orientação didático-pedagógica e educacional, além de outras providências que as atividades docentes exigirem, obedecendo a seu exclusivo critério, sem ingerência do Contratante.
                        <br><br><b>Cláusula 5ª</b> - Ao firmar o presente, o Contratante submete-se ao Regimento e Plano Escolares aprovados e às demais obrigações da legislação aplicável à área do ensino, e ainda às emanadas de outras fontes legais, desde que regulem supletivamente a matéria.
                        <br>Parágrafo único - A Escola não se responsabiliza pela guarda de pertences e objetos trazidos pelo Aluno dentro da instituição, tais como aparelhos eletrônicos e outros portáteis, moeda em dinheiro, cheque ou cartão, utensílios e objetos pessoais, uniformes, joias, adornos em geral e outros bens particulares. No entanto, se ocorrer furto, roubo ou apropriação indevida destes objetos por terceiros, em suas dependências, a Escola envidará esforços para identificar, penalizar e viabilizar a restituição pelo responsável, se o caso.
                        <br><br><b>Cláusula 6ª</b> - Como contraprestação pela prestação dos serviços prestados e a serem prestados e referentes ao período letivo de janeiro a dezembro de 2021, conforme previsto na Cláusula 1ª, o contratante obriga-se a pagar o preço da anuidade conforme estabelecida no “Requerimento de Matrícula”. 
                        <br>Parágrafo único - Em caso de desistência ou cancelamento da matrícula pelo Contratante antes do início do ano letivo, o Contratante deverá manifestar o pedido de rescisão contratual por escrito e protocolar na secretaria do estabelecimento de ensino, hipótese na qual será restituído o equivalente a 70% (setenta por cento) do valor pago. O Contratante autoriza a retenção estabelecida na forma descrita, reconhecendo as despesas administrativas da Escola. 
                        <br><br><b>Cláusula 7ª</b> - Não estão incluídos neste contrato o fornecimento de lanches e refeições, também não estão incluídos aulas de robótica,  fornecimento de materiais escolares coletivos e individuais, uniformes, eventos, despesas de transporte e excursões, apresentações culturais por grupos profissionais (cinema, etc), segunda chamada de provas, progressão parcial, provas de segunda chamada, exame médico, segundas vias de documentos, e outros serviços adicionais, que serão fixados pela escola de acordo com valores de mercado, e cobrados adicionalmente. 
                        <br>Parágrafo 1º - O percentual de desconto concedido a título de indicações de novos alunos ou bem como subsídio a irmãos de Alunos, incidirá sobre o valor, exclusivamente, proporcional à mensalidade até o dia do vencimento. 
                        <br>Parágrafo 2º - O desconto mencionado no parágrafo 1º não incidirá sobre eventos adicionais, tais como excursões, sessões de cinema, alimentação, livros e materiais didáticos, contribuições, etc.
                        <br>Parágrafo3º - O percentual de desconto aplicável às hipóteses previstas no parágrafo 1º será concedido apenas para o pagamento até a data do vencimento do boleto. Fica ciente o Contratante que o pagamento em atraso implica na perda do benefício, sendo devido o valor da anuidade/parcela de forma integral.
                        <br>Parágrafo 4º - A Escola se reserva o direito de não receber pagamentos em cheque. Caso aceite por mera liberalidade a referida forma de pagamento, a quitação somente se dará após a compensação do cheque na rede bancária.
                        <br>Parágrafo 5º - Pagamentos ocasionalmente efetuados por meios de depósitos bancários, dentro ou fora do prazo de vencimento dos boletos, somente serão considerados recebidos ou quitados depois de conferido seu valor e este corresponder ao total do montante autorizado expressamente pela área financeira da Escola. Os depósitos não identificados não comprovarão qualquer pagamento e poderão ser considerados como doação. 
                        <br><br><b>Cláusula 8ª</b> – O CONTRATANTE tem ciência, que o valor do material didático sistematizado, na modalidade para pagamento em boleto bancário, será efetivada conjuntamente com o valor da anuidade escolar, estando ciente e de acordo que havendo atraso no pagamento do boleto bancário da parcela da anuidade escolar e material didático, ficará impedido de receber o Material Didático correspondente. O CONTRATANTE tem ciência que o parcelamento do pagamento do material didático é de sua inteira responsabilidade bem e expressa seu aceite incontestavelmente.
                        <br><br><b>Cláusula 9ª</b> – A falta de pagamento na data do vencimento, além da perda do percentual de desconto previsto no parágrafo 3º supra, sujeitará o devedor ao pagamento do débito acrescido da multa de 2% (dois por cento), juros legais de 1% (um por cento) ao mês, e correção monetária pelo INPC, que serão cobrados até data do efetivo pagamento.
                        <br>Parágrafo único - Em caso de inadimplemento das obrigações decorrentes desse contrato no prazo de 30 (trinta) dias,  poderá a Escola adotar todas as providências legais de cobrança cabíveis, resguardado o direito de: (i) protestar o presente contrato, considerando se tratar de um título executivo extrajudicial nos termos do inciso III, do Art. 784, do Código de Processo Civil, reconhecendo o Contratante, desde já, este título como líquido, certo e exigível; (ii) incluir o nome do Contratante no cadastro ou serviços destinados à proteção do crédito, nos termos da Lei Estadual nº 15.659/2015, artigo 6º da Lei nº 9.870/99, artigos 475, 476 e 477 do Código Civil e artigo 43, § 2º da Lei nº 8.078 de 11.09.90; (iii) cobrar os valores extrajudicial e/ou judicialmente, inclusive por meio de terceiros devidamente contratados, ficando o Contratante sujeito a arcar com todas as custas, despesas e encargos de cobrança extrajudicial, que ora se estipula no montante de 10% (dez por cento) e ainda arcar com os honorários advocatícios de 20% (vinte por cento) que serão calculados sobre o valor total do débito na hipótese de cobrança judicial, acrescido de custas e despesas incidentes a qualquer título; (iv) rescindir o presente contrato por inadimplemento, sem a incidência de qualquer penalidade, ônus ou obrigação de ressarcir/estornar quaisquer valores por parte da Escola; e (v) recusar pedido de rematrícula do ALUNO para o ano seguinte. Referidas providências legais de cobrança poderão ser adotadas independentemente de prévia notificação do Contratante, podendo, ainda, serem tomadas isolada, gradativa ou cumulativamente. 
                        <br><br><b>Cláusula 10ª</b> - Em caso de matrícula a destempo, deverão ser feitos os pagamentos das parcelas vencidas.
                        <br><br><b>Cláusula 11ª</b> - A Escola será indenizada pelo Contratante por qualquer dano ou prejuízo que este ou o Aluno, preposto ou acompanhante de qualquer um deles, venha a causar nos edifícios, instalações, mobiliários ou equipamentos da Escola, inclusive indenização suplementar.
                        <br><br><b>Cláusula 12ª</b> - O presente contrato tem duração até o final do período letivo contratado e poderá ser rescindido nas seguintes hipóteses:
                        <br>a) Pelo Aluno e/ou responsável:
                        <br>I - por desistência formal;
                        <br>II - por transferência formal.
                        <br>b) Pela Escola:
                        <br>I - por desligamento, nos termos do Regimento Escolar;
                        <br>II - por rescisão, na forma da Cláusula 9ª.
                        <br>III - por divergências incompatibilidade entre pais e a escola visto que, invariavelmente, resulta em prejuízo do vínculo de confiança tão necessário ao sucesso da proposta educacional da instituição.
                        <br>Parágrafo 1° - Em todos os casos de rescisão acima indicados ficará o Contratante obrigado a pagar o valor da parcela do mês em que houver a rescisão, e a parcela seguinte, além de outros débitos eventualmente existentes, atualizados na forma da Cláusula 8ª.
                        <br>Parágrafo 2º- Em se tratando de rescisão do contrato de prestação de serviço educacional, o Contratante deverá efetuar o pagamento de todo o material no ato da rescisão do contrato.
                        <br>Parágrafo 3° - Em todos os casos de rescisão a Escola não será responsável pela devolução dos itens constantes da lista de materiais escolares entregue pelo Contratante para a Escola no início do ano letivo.
                        <br><br><b>Cláusula 13ª</b> - A Escola, livre de quaisquer ônus para o Contratante/Aluno, poderá utilizar-se da sua imagem e voz para fins exclusivos de divulgação da Escola e suas atividades, podendo, para tanto, reproduzi-la ou divulgá-la junto a jornais, revistas, SMS, mensagens, telefones, internet e todos os demais meios de comunicação, públicos ou privados utilizados pela Escola.
                        <br>Parágrafo único: Em nenhuma hipótese poderá a imagem ser utilizada de maneira contrária à moral ou aos bons costumes.
                        <br><br><b>Cláusula 14ª</b> – O Contratante autoriza o uso da imagem e voz do Aluno, obtidas durante as atividades desenvolvidas através das aulas remotas, bem como de sua participação em outras atividades pedagógicas não presenciais, por meio tecnológico, junto à escola.
                        <br>Parágrafo único – As imagens captadas durante o desenvolvimento das atividades remotas são de exclusiva responsabilidade do Contratante, incluindo as imagens que não tenham conteúdo pedagógico e que possam caracterizar crimes digitais.
                        <br><br><b>Cláusula 15ª</b> - A Escola, ciente das questões inerentes à nova sociedade digital, adotará política que contenha regras e procedimentos objetivando a garantia e proteção do uso de dispositivos tecnológicos e redução dos riscos de danos e prejuízos que possam comprometer a imagem, o patrimônio e os objetivos da Escola, além da orientação do uso da tecnologia a favor da educação e de todos os envolvidos no processo educacional.
                        <br>Parágrafo único: O Contratante tem ciência de que a Escola não autoriza, não compactua e não se responsabiliza pelo uso indevido de dispositivos tecnológicos dentro da Escola que possa causar dano ao Aluno, à própria Escola ou a terceiros.
                        <br><br><b>Cláusula 16ª</b> – O Contratante autoriza o tratamento dos dados pessoais, inclusive os dados pessoais sensíveis, nos termos da Lei Geral de Proteção de Dados, informados por ocasião da matrícula, inclusive a transmissão aos órgãos públicos de Educação (Municipal, Estadual ou Federal), segundo a exigência legal que a Escola deve cumprir junto a esses órgãos, bem como ao INEP – Instituto Nacional de Estudos e Pesquisas Educacionais, quando este solicitar suas informações, para fins estatísticos.
                        <br>Parágrafo único – A Escola utilizará medidas técnicas e administrativas aptas a proteger todos os dados informados pelo Contratante.
                        <br><br><b>Cláusula 17ª</b> - Considerando que o presente contrato é firmado antecipadamente, com previsão de início da prestação dos serviços para o ano letivo de 2021, fica assegurada a possibilidade de alteração dos valores pela Escola, incluindo a revisão do percentual de descontos de estudos, bem como subsídio a irmãos de Alunos, de modo a preservar o equilíbrio contratual, caso ocorra qualquer mudança legislativa ou normativa que altere a prestação dos serviços ou a equação econômico-financeira do presente instrumento, em especial, considerando a pandemia da COVID-19, causada pelo novo coronavírus, declarada pela Organização Mundial da Saúde (OMS) no dia 11/03/2020 e o estado de calamidade pública reconhecido pelo Decreto Legislativo nº 6 de 20/03/2020. 
                        <br>Parágrafo 1º - Em ocorrendo a situação acima prevista antes do início do ano letivo e caso o Contratante não concorde em aderir à alteração proposta a tempo e modo, o Contratante deverá manifestar o pedido de rescisão contratual por escrito e protocolar na secretaria da Escola, hipótese na qual será restituído o equivalente a 70% (setenta por cento) do valor pago. O Contratante autoriza a retenção estabelecida na forma descrita, reconhecendo as despesas administrativas da Escola. 
                        <br>Parágrafo 2º - Em ocorrendo a situação acima prevista após o início do ano letivo e caso o Contratante não concorde em aderir à alteração proposta a tempo e modo, o Contratante deverá manifestar o pedido de rescisão contratual por escrito e protocolar na secretaria da Escola, hipótese em que ficará o Contratante obrigado a pagar o valor da parcela do mês em que houver a rescisão, e a parcela seguinte, além de outros débitos eventualmente existentes, atualizados na forma da Cláusula 8ª.
                        <br>Parágrafo 3º - Em se tratando de rescisão do contrato de prestação de serviço educacional do programa bilíngue e do amigavelmente, sendo o material da Pearson recebido integralmente pelo Aluno, o Contratante deverá efetuar o pagamento de todo o material no ato da rescisão do contrato.
                        <br><br><b>Cláusula 18ª</b> - O Contratante, ciente do Regimento Interno da Escola, deverá declarar por escrito, no ato da matrícula, se o Aluno possui deficiência definida nos termos do artigo 2°. da Lei 13.146/2015 – Estatuto do Deficiente.
                        <br>Parágrafo 1° - Considera-se pessoa com deficiência aquela que tem impedimento de longo prazo de natureza física, mental, intelectual ou sensorial, o qual, em interação com uma ou mais barreiras, pode obstruir sua participação plena e efetiva na sociedade em igualdade de condições com as demais pessoas.
                        <br>Parágrafo 2° - É indispensável e de inteira responsabilidade dos pais a apresentação do Laudo de Avaliação a Escola para o efetivo cumprimento dos serviços especiais oferecidos, em cumprimento às disposições legais previstas no Estatuto do Deficiente, Lei nº 13.146/2015, podendo a Escola, outrossim, requerer o fornecimento, pelo Contratante, de avaliações e/ou laudos de saúde fornecidos por profissionais especializados com o objetivo de colaborar com o processo de avaliação de eventual deficiência e a fim de dar cumprimento à elaboração do plano de desenvolvimento individual pedagógico do Aluno, visando o maior aproveitamento de suas competências.
                        <br>Parágrafo 3º - As informações acima, também, deverão ser fornecidas se o Aluno vier a adquirir alguma deficiência, de acordo com a Lei nº 13.146/2015, no decorrer das atividades letivas.
                        <br>Parágrafo 4º - A não observância do disposto acima pelos Contratantes poderá ensejar a propositura de medidas extrajudiciais, como comunicação ao Conselho Tutelar, e medidas judiciais, se ficar comprovado real prejuízo ao Aluno.
                        <br><br><b>Cláusula 19ª</b> - Se durante a vigência do presente contrato ocorrer a substituição do responsável financeiro do Aluno por morte, separação ou por qualquer outra causa, os genitores deverão informar por escrito a Escola, no prazo de 30 dias. 
                        <br><br><b>Cláusula 20ª</b> - Se alguma disposição contida neste Contrato for considerada inválida, ilegal ou inexequível sob qualquer aspecto, as demais disposições contidas neste instrumento, não serão, de forma alguma, afetadas ou prejudicadas, sendo tolerância de uma das partes quanto ao descumprimento de qualquer obrigação da outra parte será considerada mera liberalidade, não implicando em novação e tampouco em renúncia do direito de qualquer que uma possa exigir da outra. 
                        <br><br><b>Cláusula 21ª</b> - O presente Instrumento é celebrado sob a égide da Constituição Federal, da Lei nº 10.406 de 10/01/2002 (Código Civil Brasileiro), da Lei nº 9.870 de 23/11/99, da Lei n° 8.078 de 11/09/90 e legislação pertinente, tem caráter irrevogável e irretratável, possuindo plena eficácia e valendo como título executivo extrajudicial.
                        <br><br><b>Cláusula 22ª</b> - Para dirimir questões oriundas deste contrato, fica eleito o foro da comarca de Formosa, GO.
                        <br>E, por estarem justos e contratados, assinam o presente instrumento em duas vias de igual teor e forma, na presença das testemunhas abaixo, para que se produzam todos os efeitos legais.


                    </p>
                        

                    <center>{{$unidadeEnsino->cidade_uf}}, {{date('d/m/Y', strtotime($matricula->data_matricula))}}.</center>

                    <br><br>
                    <table align="center">
                        <tr>
                            <td width="300px">
                                <hr>         
                                
                                <b>
                                    {{$matricula->responsavel->nome}} <br>
                                    Doc. Ident.: {{$matricula->responsavel->tipoDocIdentidade->tipo_doc_identidade ?? ''}}-{{$matricula->responsavel->doc_identidade}} <br>
                                    CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->responsavel->cpf)}}
                                </b>            
                            </td>
                            <td width="50px"></td>
                            <td width="300px">
                                
                                <hr>            
                                <b>                                    
                                    {{$unidadeEnsino->razao_social}} <br>
                                    {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}
                                    <br>
                                    <br>
                                </b>            
                            </td>
                        </tr>
                    </table>
                    <br>
                    
                    <table align="center">
                        <tr>
                            <td colspan="="2>Testemunhas:</td>
                        </tr>
                        <tr>
                            <td width="300px">
                                <br>
                                <hr>                                
                                Nome: <br>                                
                                R.G.:                                         
                            </td>
                            <td width="50px"></td>
                            <td width="300px">
                                <br>
                                <hr>                                
                                Nome: <br>                                
                                R.G.:                                         
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table> 
</body>
</html>