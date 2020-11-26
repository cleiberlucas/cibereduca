<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Requerimento Matrícula {{$matricula->aluno->nome}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <script type="text/javascript" src="{!!asset('/js/utils.js')!!}"></script>
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
    .row {    
        margin-top: -0.25rem !important;
        /* margin-right: 5; */
        margin-bottom: 0;
        /* padding-right: 5; */
        padding-top: 0;
        padding-bottom: 0
    }
    p{
        font-size: 20px;
    }
    html {
        height: 96%;
    }

    body {
        min-height: 100%;
        display: grid;
        grid-template-rows: 1fr auto;
    }

    .footer {
        grid-row-start: 2;
        grid-row-end: 3;
    }

</style>

<body>

    <div class="container-fluid">
        <div class="content">
   
            <table>        
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
                    <td colspan=2>                        
                        <br>
                        Ilmo. Sr. Diretor do {{mb_strToUpper($unidadeEnsino->razao_social)}}
                    </td>
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
            
            <table border="1" cellspacing="0">
                <tr>
                    <td colspan=2>                    
                        <strong>DADOS PESSOAIS DO(A) ALUNO(A):</strong>
                        <br>
                        <strong>Nome do(a) aluno(a): {{$matricula->aluno->nome}}</strong>
                        <br>
                        Data nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}}
                        &nbsp;&nbsp;
                        Sexo: {{$matricula->aluno->sexo->sexo}}                        
                        <br>
                        <strong>Nome do pai:</strong> {{$matricula->aluno->pai}}                    
                        &nbsp;&nbsp;
                        <strong>Nome da mãe:</strong> {{$matricula->aluno->mae}}
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <strong>RESPONSÁVEL PELA MATRÍCULA</strong>
                        <br>
                        <strong>Responsável Financeiro: {{$matricula->responsavel->nome}}</strong>
                        <br>
                        Data nascimento: {{date('d/m/Y', strtotime($matricula->responsavel->data_nascimento))}}
                        &nbsp;&nbsp;&nbsp;Sexo: {{$matricula->responsavel->sexo->sexo}}
                        &nbsp;&nbsp;&nbsp;Doc. Identidade: {{$matricula->responsavel->doc_identidade}} {{$matricula->responsavel->tipoDocIdentidade->tipo_doc_identidade ?? ''}} 
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
                        Telefones: {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_1)}}  e {{mascaraTelefone("(##) #####-####", $matricula->responsavel->telefone_2)}}   &nbsp&nbsp&nbsp E-mail: {{$matricula->responsavel->email_1}}
                        <br>
                        Profissão: {{$matricula->responsavel->profissao}} - Empresa: {{$matricula->responsavel->empresa}}                
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <strong>VALOR, VENCIMENTO E PRAZO:</strong>
                        <br>
                        O preço da anuidade é de <strong>R$ {{number_format($matricula->turma->tipoTurma->valor_curso, 2, ',', '.')}}</strong>
                        que poderá ser pago à vista com cinco por cento de desconto no ato da matrícula, ou em {{$matricula->qt_parcelas_curso+1}} parcela(s) assim distribuídas:
                        <br>
                        a) A primeira no ato da matrícula no valor de R$ {{number_format($matricula->valor_matricula, 2, ',', '.')}};
                        @if ($matricula->qt_parcelas_curso > 1)
                            <br>
                            b) As parcelas de 2 a {{$matricula->qt_parcelas_curso+1}} no valor de R$ 
                            {{number_format(($matricula->turma->tipoTurma->valor_curso-$matricula->valor_desconto)/$matricula->qt_parcelas_curso, 2, ',', '.') }} 
                            cada, que serão pagas sucessivamente nos meses de {{nomeMes(date('m', strtotime($matricula->data_venc_parcela_um) ) )}} 
                            a {{nomeMes(date('m', strtotime($matricula->data_venc_parcela_um))+$matricula->qt_parcelas_curso-1) }}  
                            de {{date('Y', strtotime($matricula->data_venc_parcela_um)) }}, 
                            com vencimento todo dia  {{date('d', strtotime($matricula->data_venc_parcela_um)) }}.
                        @endif                                 
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td colspan=2>                        
                        OBS: Pedidos de mudança de turma ou de período, de atuais alunos, devem ser feitos através de formulário próprio, mediante a disponibilidade de vaga.
                        Casos especiais (separação judicial, alteração na guarda de menor, responsável financeiro diverso dos pais, etc.) devem ser comunicados por escrito.
                        <br>Declaro que neste ato assinei o Contrato de Prestação de Serviços Educacionais (v. verso) e tomei ciência de todas as suas cláusulas, com as quais concordo.                        
                    </td>
                </tr>        
            </table>
            <br>            
            <table>
                <tr>
                    <td width="200px">{{$unidadeEnsino->cidade_uf}}, {{date('d/m/Y', strtotime($matricula->data_matricula))}}.</td>
                    <td>Ass. Responsável Financeiro: ______________________________________</td>
                </tr>
            </table>                
            <br>
            <table align="center">
                <tr>
                    <td width="300px">
                        <hr>
                        <center>
                            TESTEMUNHA 1
                        </center>
                        
                        Nome:
                        <br>
                        CPF:            
                
                    </td>
                    <td width="50px"></td>
                    <td width="300px" >
                        <hr>
                        <center>                
                            TESTEMUNHA 2
                        </center>
                        
                        Nome:                
                        <br>
                        CPF:
                        
                    </td>
                </tr>
            </table>
            <hr>
            USO EXCLUSIVO DO COLÉGIO:
            <table border="1" cellspacing="0">
                <tr>
                    <td width="200 px">Parecer do Diretor</td>
                    <td width="200 px">(&nbsp;&nbsp;&nbsp;) Matrícula Deferida</td>
                    <td width="200 px">(&nbsp;&nbsp;&nbsp;) Matrícula Indeferida</td>
                </tr>
                <tr>
                    <td>Assinatura do Diretor:</td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </div>
    </div>
    @include('secretaria.paginas._partials.rodape_redeeduca')       
                
</body>
</html>
