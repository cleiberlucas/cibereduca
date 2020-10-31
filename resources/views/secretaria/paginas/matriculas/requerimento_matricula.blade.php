<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Requerimento Matrícula {{$matricula->aluno->nome}}</title>
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
   
    <table >        
        <tr>
            <td class="report-header-cell" width="15%">
                <div class="header-info" align="left">
                    @include('secretaria.paginas._partials.cabecalho_redeeduca')
                </div>
            </td>              
        </tr>        
        <tr>
            <td colspan=2>                
                <center><strong>REQUERIMENTO DE MATRÍCULA - ANO LETIVO DE {{$matricula->turma->tipoTurma->anoLetivo->ano}}</strong></center>                    
            </td>
        </tr>            
        <tr>
            <td colspan=2>Ilmo. Sr. Diretor do {{mb_strToUpper($unidadeEnsino->razao_social)}}</td>
        </tr>
        <tr>
            <td colspan=2>
                O(a) aluno(a) neste ato representado(a) por seu responsável abaixo-assinado, requer sua matrícula, no ano letivo de {{$matricula->turma->tipoTurma->anoLetivo->ano}},
                conforme segue:
                <br>
                <strong>Nível de ensino: {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}</strong>
                <br>
                <strong>Série/Ano: {{$matricula->turma->nome_turma}} </strong>
                <br>
                <strong>Modalidade / Período: {{$matricula->turma->turno->descricao_turno}}</strong>
            </td>
        </tr>        
    </table>
    <br>
    <table border="1" cellspacing="0">
        <tr>
            <td colspan=2>                    
                <strong>DADOS PESSOAIS DO(A) ALUNO(A):</strong>
                <br>
                <p><strong>Nome do(a) aluno(a): {{$matricula->aluno->nome}}</strong>
                <br>
                Data nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}}
                &nbsp;&nbsp;
                Sexo: {{$matricula->aluno->sexo->sexo}}                        
                <br>
                <strong>Nome do pai:</strong> {{$matricula->aluno->pai}}                    
                &nbsp;&nbsp;
                <strong>Nome da mãe:</strong> {{$matricula->aluno->mae}}</p>                    
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <strong>RESPONSÁVEL PELA MATRÍCULA</strong>
                <br>
                <p><strong>Responsável Financeiro: {{$matricula->responsavel->nome}}</strong>
                <br>
                Data nascimento: {{date('d/m/Y', strtotime($matricula->responsavel->data_nascimento))}}
                &nbsp;&nbsp;&nbsp;Sexo: {{$matricula->responsavel->sexo->sexo}}
                &nbsp;&nbsp;&nbsp;Doc. Identidade: {{$matricula->responsavel->doc_identidade}} {{$matricula->responsavel->tipoDocIdentidade->tipo_doc_identidade}}
                &nbsp;&nbsp;&nbsp;CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->responsavel->cpf)}}
                
                
                <br>
                Endereço: {{$matricula->responsavel->endereco->endereco ?? ''}} - {{$matricula->responsavel->endereco->complemento ?? ''}} &nbsp&nbsp&nbsp Nº {{$matricula->responsavel->endereco->numero ?? ''}}
                <br>
                Bairro: {{$matricula->responsavel->endereco->bairro ?? ''}} &nbsp&nbsp&nbspCidade: {{$matricula->responsavel->endereco->cidade->cidade ?? ''}}/{{$matricula->responsavel->endereco->cidade->estado->sigla ?? ''}}    
                &nbsp&nbsp&nbsp CEP: 
                @if (isset($matricula->responsavel->endereco->cep)) 
                    {{mascaraCEP('##.###-###', $matricula->responsavel->endereco->cep) ?? ''}}
                @endif
                <br>
                Telefones: {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_1)}}  e {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_2)}}   &nbsp&nbsp&nbsp E-mail: {{$matricula->responsavel->email_1}}
                <br>
                Profissão: {{$matricula->responsavel->profissao}} - Empresa: {{$matricula->responsavel->empresa}}
                </p>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <strong>VALOR, VENCIMENTO E PRAZO:</strong>
                <p>
                O preço da anuidade é de <strong>R$ {{number_format($matricula->turma->tipoTurma->valor_curso, 2, ',', '.')}}</strong>
                que poderá ser pago à vista com cinco por cento de desconto no ato da matrícula, ou em 12 parcelas assim distribuídas:

                </p>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan=2>
                <p align="justify">OBS: Pedidos de mudança de turma ou de período, de atuais alunos, devem ser feitos através de formulário próprio, mediante a disponibilidade de vaga.
                Casos especiais (separação judicial, alteração na guarda de menor, responsável financeiro diverso dos pais, etc.) devem ser comunicados por escrito.
                <br>Declaro que neste ato assinei o Contrato de Prestação de Serviços Educacionais (v. verso) e tomei ciência de todas as suas cláusulas, com as quais concordo.
                <br>
                Formosa-GO, {{date('d/m/Y', strtotime($matricula->data_matricula))}}.
                </p>
            </td>
        </tr>
    </table>
            {{--  <td colspan=2>
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
            </tr> --}}
                 
            
            
        <table align="center">
            <tr>
                <td width="500px" align="center">
                    <hr>            
                    <b>ASSINATURA DO RESPONSÁVEL FINANCEIRO</b>                                                                    
                </td>                            
            </tr>
        </table>
        <br>
        <br>
        <table align="center">
            <tr>
                <td width="300px">
                    <hr>
                    <center>
                        TESTEMUNHA 1 <br>
                    </center>
                    CPF:                 
            
                </td>
                <td width="300px" >
                    <hr>
                    <center>                
                        TESTEMUNHA 2 <br>
                    </center>
                    CPF:                
                    
                </td>
            </tr>
        </table>
        @include('secretaria.paginas._partials.rodape_redeeduca')
                
</body>
</html>