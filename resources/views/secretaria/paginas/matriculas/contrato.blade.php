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
               <td class="report-header-cell" width="15%">
                  <div class="header-info" align="left">
                        <img src="/vendor/adminlte/dist/img/logo.png" width="70%" alt="logo">
                  </div>
               </td>
               <td class="report-header-cell" align="center">
                    <div class="header-info">
                        <font size="4"> <b>{{mb_strToUpper($unidadeEnsino->razao_social)}} </b> </font>
                        <br>
                        <font size="2"> CNPJ: {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}
                        <br>
                        Fone: 
                        @if (strlen($unidadeEnsino->telefone) == 10)
                            {{mascaraTelefone('(##) ####-####', $unidadeEnsino->telefone)}} - E-mail: {{$unidadeEnsino->email}}
                        @else
                            {{mascaraTelefone('(##) #####-####', $unidadeEnsino->telefone)}} - E-mail: {{$unidadeEnsino->email}}    
                        @endif
                        <br>
                        {{$unidadeEnsino->endereco}}
                        
                        </font>
                    </div>
               </td>
            </tr>
        </thead>
        <tbody class="report-content">
            <tr>
                <td colspan=2>
                    <br>
                    <center><strong> <mark>CONTRATO DE PRESTAÇÃO DE SERVIÇOS EDUCACIONAIS E COMPRA DE MATERIAL DIDÁTICO ENSINO FUNDAMENTAL E MÉDIO</mark></strong></center>                    
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
                    <br><br>
                    <strong>QUADRO 02: ALUNO(A)</strong>
                    <br>
                    Nome: {{$matricula->aluno->nome}}   &nbsp&nbsp&nbsp Série/Ano: {{$matricula->turma->nome_turma}} - {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino ?? ''}} - {{$matricula->turma->tipoTurma->anoLetivo->ano ?? ''}}
                    <br>
                     Data de Nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}} &nbsp&nbsp&nbsp CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->aluno->cpf)}}    &nbsp&nbsp&nbsp Fone: {{mascaraTelefone("(##) #####-####",$matricula->aluno->telefone_1)}}
                    
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br><br>
                    <strong>QUADRO 03: CONTRATADA</strong>
                    <br>
                    {{$unidadeEnsino->razao_social}}, inscrita no CNPJ sob o n° {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}, Matriz, sediada à {{$unidadeEnsino->endereco}}.
                    
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br><br>
                    <strong>QUADRO 04: CURSO {{mb_strtoupper($matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino) ?? ''}} E MATERIAL DIDÁTICO DO SISTEMA DE ENSINO DOM BOSCO</strong>                    
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br><br>
                    <strong>QUADRO 05: DESCRIÇÃO DO INVESTIMENTO</strong>
                    <br><br>
                    <strong>5.1 - MATRÍCULA</strong>
                    R$ {{number_format($matricula->valor_matricula, 2, ',', '.')}}      &nbsp&nbsp&nbsp PAGTO: {{date('d/m/Y', strtotime($matricula->data_pagto_matricula))}}      &nbsp&nbsp&nbsp Forma de Pagto: {{$matricula->formaPagamentoMatricula->forma_pagamento ?? ''}}
                    <br><br>
                    <strong>5.2 - CURSO</strong>
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
                    <strong>5.3 - MATERIAL DIDÁTICO DOM BOSCO E COLEÇÃO AMIGAVELMENTE (1º AO 9º ANO):</strong>
                    <br>
                    QTD: {{$matricula->qt_parcelas_mat_didatico}} PARCELA(S) - VLR UNITÁRIO: R$ 
                    @if ($matricula->qt_parcelas_mat_didatico > 0)
                        {{number_format($matricula->valor_material_didatico/$matricula->qt_parcelas_mat_didatico, 2, ',', '.') ?? ''}} 
                    @endif
                    &nbsp&nbsp&nbsp Forma de Pagto: {{$matricula->formaPagamentoMaterialDidatico->forma_pagamento ?? ''}} &nbsp&nbsp&nbsp 1º Pagto: {{date('d/m/Y', strtotime($matricula->data_pagto_mat_didatico))}}
                    <br>
                    <strong>VLR TOTAL MATERIAL DIDÁTICO: R$ {{number_format($matricula->valor_material_didatico, 2, ',', '.') ?? ''}}</strong> 
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan=2>                    

                    <center><b><u>CLÁUSULA PRIMEIRA – DO OBJETO DO CONTRATO</u></b></center>
                    <p align="justify">O objeto deste contrato é a prestação de serviços educacionais e compra do material didático selecionados nas opções descritas no quadro 04.</p>
                    <p><u><b>Parágrafo Primeiro</u></b>: Caso o beneficiário do contrato venha a se enquadrar na condição de aluno com necessidades educacionais especiais, deverá o CONTRATANTE arcar com o pagamento dos respectivos custos, os quais serão discriminados em instrumento específico, sendo igualmente de responsabilidade do CONTRATANTE o pagamento dos profissionais de saúde que atendam o(a) ALUNO(A).
                    </p>
                    <center><b><u>CLÁUSULA SEGUNDA – DA MATRÍCULA</u></b></center>
                    <p align="justify">Obriga-se o CONTRATANTE, para a configuração formal do ato de matrícula, ao preenchimento dos formulários próprios fornecidos pela CONTRATADA, os quais passam a fazer parte deste instrumento. <br>
                    <b><u>Parágrafo Primeiro</u></b>: A matrícula e o contrato somente se efetivam com a assinatura pelas PARTES deste instrumento contratual, podendo a CONTRATADA recusá-los caso sejam desrespeitados quaisquer prazos, condições e formas estabelecidas pela CONTRATADA em relação às obrigações do(a) ALUNO(A) e seu responsável legal, assim como nos casos em que o(a) ALUNO(A) não satisfaça as exigências aplicáveis da legislação de ensino e o CONTRATANTE esteja inadimplente com relação a quaisquer parcelas do ano letivo anterior. <br>
                    <b><u>Parágrafo Segundo</u></b>: O CONTRATANTE assume total responsabilidade quanto às declarações prestadas neste contrato e nos demais documentos de matrícula, relativas à aptidão legal do(a) aluno(a) para a frequência na série indicada, quando for o caso, concordando, desde já, que a não entrega dos documentos exigidos pela CONTRATADA até o dia do início do ano letivo de 2020, acarretará o automático cancelamento da vaga aberta ao(a) aluno(a), rescindindo-se o presente contrato, encerrando-se a prestação de serviços e isentando a CONTRATADA de qualquer responsabilidade pelos eventuais danos resultantes. <br>
                    <b><u>Parágrafo Terceiro</u></b>: Recebida a documentação exigida para matrícula, deverá a CONTRATADA, nos 30 (trinta) dias subsequentes, aferir a sua regularidade. Caso a documentação não preencha os requisitos legais para a matrícula do(a) ALUNO(A), o fato acarretará a rescisão imediata deste contrato, com o consequente cancelamento da vaga aberta ao(a) ALUNO(A). Na hipótese de a prestação de serviços educacionais já tiver sido iniciada, o valor da anuidade será cobrado proporcionalmente a esse prazo. Caso contrário, os valores eventualmente pagos serão restituídos ao CONTRATANTE, na forma descrita na clausula quarta. <br>
                    <b><u>Parágrafo Quarto</u></b>: O requerimento de matrícula somente será encaminhado para exame e deferimento pela diretoria da CONTRATADA após certificação de que o CONTRATANTE tenha quitado todos os seus débitos e demais obrigações previstas para pagamento no ato da matrícula.
                    </p>
                    <center><b><u>CLÁUSULA TERCEIRA – DA ANUIDADE</u></b></center>
                    <p align="justify">O CONTRATANTE declara que teve conhecimento prévio das condições financeiras do contrato, o qual foi apresentado no ato da matrícula, aceitando-as livremente. <br>
                    <b><u>Parágrafo Primeiro</u></b>: Como contraprestação pela prestação dos serviços descritos na cláusula segunda e selecionados no QUADRO 04 – CURSO E MATERIAL DIDÁTICO referentes ao período letivo de 2020, o CONTRATANTE pagará à CONTRATADA os valores descritos no QUADRO 05 – INVESTIMENTO supra. <br>
                    <b><u>Parágrafo Segundo</u></b>: Eventual abatimento, desconto ou redução no valor das 12(doze) parcelas da anuidade será ajustado como parte integrante do presente contrato e, quando ocorrer, constituirá mera liberalidade da CONTRATADA. Fica ciente o CONTRATANTE que o pagamento em atraso implicará na perda de tal benefício, conforme parágrafo nono, sendo devido o valor da anuidade definido no parágrafo Sétimo. <br>
                    <b><u>Parágrafo Terceiro</u></b>: O presente contrato não inclui o fornecimento de materiais de uso individual do ALUNO, uniformes, carteiras de identificação, transporte escolar, alimentação, assim como outros serviços extraordinários, os quais citamos, exemplificativamente, aulas especiais de recuperação, reforço, estudos de adaptação, progressão parcial, segunda via de boletins, declarações diversas, dentre outros que não integrem a rotina do cotidiano educacional. <br>
                    <b><u>Parágrafo Quarto</u></b>: O valor da anuidade poderá ser pago em parcela única (à vista) ou de acordo com o plano alternativo abaixo descrito, em conformidade com a Lei nº 9.870/99. O pagamento de qualquer parcela não gera presunção de quitação de parcelas anteriores eventualmente não quitadas. <br>
                    <b><u>Parágrafo Quinto</u></b>: Caso o pagamento seja efetuado após a data de vencimento ajustada acima, será cobrado o valor da mensalidade constante no quadro 04 e será acrescido de multa contratual de 2% (dois por cento), dos juros de mora de 1% (um por cento) ao mês, além da correção monetária apurada com base no INPC. Em caso de ação judicial para a cobrança dos valores devidos, o CONTRATANTE arcará com os custos advindos do processo, inclusive honorários advocatícios. <br>
                    <b><u>Parágrafo Sexto</u></b>: Tem ciência, neste ato, o CONTRATANTE que, em caso de inadimplência das parcelas ou qualquer obrigação de pagamento decorrente do presente contrato por 60 (sessenta) dias ou mais, poderá a CONTRATADA, além de não renovar a matrícula do ALUNO para o período letivo seguinte,valer-se dos meios administrativos e judiciais cabíveis para a cobrança de seu crédito, reservando-se o direito de inscrever o nome do CONTRATANTE em bancos de dados cadastrais (SPC/SERASA) e de valer-se de firma especializada, respondendo também, neste caso, o CONTRATANTE, por honorários a ela devidos. <br>
                    <b><u>Parágrafo Sétimo</u></b>: A CONTRATADA poderá valer-se do contrato para emitir, e, se for o caso, emitir e protestar duplicatas, notas promissórias, cheques sem previsão de fundos.Autoriza, desde logo, o CONTRATANTE, que a CONTRATADA emita referidos títulos/ordens, sendo expresso o aceite pela assinatura do presente contrato. <br>
                    <b><u>Parágrafo Oitavo</u></b>: O CONTRATANTE tem ciência, que o valor do material didático sistematizado, na modalidade para pagamento em boleto bancário, será efetivada conjuntamente com o valor da anuidade escolar, estando ciente e de acordo que havendo atraso no pagamento do boleto bancário da parcela da anuidade escolar e material didático, ficará impedido de receber o Material Didático correspondente. <br>
                    <b><u>Parágrafo Nono</u></b>: O CONTRATANTE tem ciência das formas de pagamento, descritas no QUADRO 05 – INVESTIMENTO, aceitas pela CONTRATADA e expressa seu aceite incontestavelmente.  <br>
                    <b><u>Parágrafo Décimo</u></b>: Em caso de inadimplência, o CONTRATANTE perderá todo e qualquer desconto do qual eventualmente seja beneficiário.
                    </p>
                    <center><b><u>CLÁUSULA QUARTA – DO ENCERRAMENTO DO CONTRATO</u></b></center>
                    <p align="justify">O presente contrato poderá ser rescindido nas seguintes situações: <br>
                    I. Por iniciativa do CONTRATANTE, mediante requerimento escrito protocolado junto à Secretaria da CONTRATADA, com antecedência mínima de 30 (trinta) dias. <br>
                    II. Por iniciativa da CONTRATADA, ao final do ano letivo, automaticamente, quando verificada inadimplência de quaisquer das parcelas devidas. Nesta hipótese, desobriga-se a CONTRATADA à análise do requerimento de renovação de matrícula, sem prejuízo da exigibilidade do débito vencido, com os acréscimos previstos na cláusula TERCEIRA. <br>
                    III. Por iniciativa da CONTRATADA, com expedição imediata dos documentos de transferência do aluno, por motivo de indisciplina, infração ao Regimento Interno da CONTRATADA, incompatibilidade do(a)ALUNO(A) ou de sua família com a proposta da escola e/ou de divergência ou conflito entre o CONTRATANTE. <br> 
                    IV. Por acordo entre as PARTES, ajustado por escrito. <br>
                    <b><u>Parágrafo Primeiro</u></b>: Fica o CONTRATANTE obrigado a quitar o valor integral da parcela do mês em que o requerimento for protocolado, além de outros débitos eventualmente existentes. <br>
                    <b><u>Parágrafo Segundo</u></b>: Caso o CONTRATANTE formalize pedido formal de desistência até o dia 17/01/2020, a CONTRATADA efetuará a devolução de 60% dos valores pagos, ficando o restante destinado a cobrir as despesas administrativas, tributos e contribuições incidentes sobre o pagamento que tenham sido suportados pela CONTRATADA. Se a desistência ocorrer depois do dia 20/01/2020, não serão devolvidos quaisquer valores.
                    </p>
                    <center><b><u>CLÁUSULA QUINTA – DAS RESPONSABILIDADES</u></b></center>
                    <p align="justify">Ao firmar o presente contrato o CONTRATANTE declara que tem conhecimento prévio do Regimento Escolar e das instruções específicas, que lhe foram apresentados e que passam a fazer parte integrante do presente contrato, submetendo-se às suas disposições, bem como das demais obrigações decorrentes da legislação aplicável à área de ensino. <br>
                    <b><u>Parágrafo Primeiro</u></b>: O CONTRATANTE fica ciente, desde logo, da obrigatoriedade do uso completo do uniforme escolar,bem como da aquisição de todo o material escolar individual exigido, material didático, assumindo inteiramente a responsabilidade por qualquer prejuízo acadêmico que o aluno venha a enfrentar em decorrência do descumprimento desta obrigação. <br>
                    <b><u>Parágrafo Segundo</u></b>: Declara-se ciente o CONTRATANTE de que é proibido ao(a) ALUNO(A) a utilização de telefone celular ou outro aparelho eletrônico durante as atividades didático-pedagógicas, ficando a CONTRATADA autorizada a adotar as medidas disciplinares cabíveis nas hipóteses de descumprimento desta proibição. <br>
                    <b><u>Parágrafo Terceiro</u></b>: A CONTRATADA não se responsabilizará por pertences trazidos pelo(a) ALUNO(A) para o interior da escola, em quaisquer de suas dependências, telefones celulares, máquinas fotográficas, dinheiro, bem como de materiais de uso individual relacionados ao objeto deste instrumento. <br>
                    <b><u>Parágrafo Quarto</u></b>: O CONTRATANTE fica ciente ainda de que a CONTRATADA não presta quaisquer tipos de serviços de estacionamento, vigilância ou guarda de veículos automotores de qualquer natureza, bicicletas e outros tipos de transporte, não assumindo, portanto, a responsabilidade perante indenizações por danos, furtos, roubos, incêndios, atropelamentos, colisões e outros eventos que venham a ocorrer, nas dependências internas ou externas da CONTRATADA, cabendo, nestes casos, ao condutor e/ou proprietário do meio de transporte, a exclusiva responsabilidade pela reparação de danos. <br>
                    <b><u>Parágrafo Quinto</u></b>: O CONTRATANTE exime a CONTRATADA de qualquer responsabilidade quanto à guarda e/ou ressarcimento dos bens previstos no parágrafo acima. Fica ciente o CONTRATANTE, ainda, de sua responsabilidade pela reparação de quaisquer danos ocasionados pelo(a) ALUNO(A) em patrimônio da CONTRATADA, em atividades nas dependências da escola ou fora dela quando em eventos externos patrocinados pela mesma, ou a TERCEIROS, sejam estes de natureza pessoal, moral ou material. <br>
                    <b><u>Parágrafo Sexto</u></b>: Declara-se ciente o CONTRATANTE de que é proibido ao(a) ALUNO(A) a utilização de telefone celular ou outro aparelho eletrônico durante as atividades didático-pedagógicas, ficando a CONTRATADA autorizada a adotar as medidas disciplinares cabíveis nas hipóteses de descumprimento desta proibição. <br>
                    <b><u>Parágrafo Sétimo</u></b>: A CONTRATADA, livre de quaisquer ônus para com o CONTRATANTE e o(a) ALUNO(A), poderá utilizar-se da imagem do(a) ALUNO(A) para fins exclusivos de divulgação da escola e de suas atividades podendo, para tanto, reproduzi-la ou divulgá-la junto à internet, jornais, periódicos diversos e todos os demais meios de comunicação, público ou privado. <br>
                    </p>
                    <center><b><u>CLÁUSULA SEXTA – DA VIGÊNCIA</u></b></center>
                    <p align="justify">O presente contrato terá vigência desde a data de sua assinatura até 31 de dezembro de {{$matricula->turma->tipoTurma->anoLetivo->ano}}. <br>
                    </p>
                    <center><b><u>CLÁUSULA SÉTIMA – DO FORO</u></b></center>
                    <p align="justify">Para dirimir questões oriundas deste contrato fica eleito o Foro da Comarca em cuja jurisdição o CONTRATANTE tiver domicílio. <br>
                    E, por estarem as PARTES de acordo com todos os termos e condições do presente contrato, assinam este instrumento em duas vias de igual teor e forma, na presença das testemunhas abaixo, para que se produzam todos os efeitos legais. <br>
                    </p>
                    <center>Formosa-GO, {{date('d/m/Y', strtotime($matricula->data_matricula))}}.</center>

                    <br><br>
                    <table align="center">
                        <tr>
                            <td width="300px" align="center">
                                <hr>            
                                <b>
                                    CONTRATANTE <br>
                                    CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->responsavel->cpf)}}
                                </b>            
                            </td>
                            <td width="300px" align="center">
                                <hr>            
                                <b>
                                    CONTRATADA <br>
                                    {{$unidadeEnsino->razao_social}} 
                                </b>            
                            </td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <table align="center">
                        <tr>
                            <td width="250px">
                                <hr>
                                <center>
                                    TESTEMUNHA 1 <br>
                                </center>
                                CPF:                 
                        
                            </td>
                            <td width="250px">
                                <hr>
                                <center>                
                                    TESTEMUNHA 2 <br>
                                </center>
                                CPF:                
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table> 
</body>
</html>