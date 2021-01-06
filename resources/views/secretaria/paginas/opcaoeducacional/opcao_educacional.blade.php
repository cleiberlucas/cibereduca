
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicons/favicon.ico" >
    <title>Opção Educacional
    </title> 
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        
</head>
<style>   
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
    <div class="container-fluid ">        
        @include('secretaria.paginas._partials.cabecalho_redeeduca')
        <div class="content">
            <div class="row my-0">
                <div class="form-group col-sm-11 col-xs-2 my-5 py-0" >                                                                              
                    <h4><center><strong> TERMO DE OPÇÃO EDUCACIONAL </strong></center></h4>                    
                </div>
            </div>
            
            <div class="row ">
                <div class="form_group col-sm-12 my-0 py-0">
                    <h5>Responsável: {{$opcaoEducacional->responsavel}}</h5>                                        
                    <h5>Aluno(a): {{$opcaoEducacional->aluno}} &nbsp;&nbsp;&nbsp;&nbsp; Data Nascimento: {{date('d/m/Y', strtotime($opcaoEducacional->data_nascimento))}}</h5>                    
                    <h5>Sexo: {{$opcaoEducacional->sexo}} &nbsp;&nbsp;&nbsp;&nbsp; Série: {{$opcaoEducacional->nome_turma}} - {{$opcaoEducacional->descricao_turno}}</h5>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-xs-2 my-0 py-0" align="justify">
                    <p>
                        <br>
                        Eu (nós) responsável(eis) legal(is) pelo aluno indicado, opto(amos) livremente pelo formato de
                        ensino abaixo indicado, a partir da permissão de reabertura da escola:                   
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-12 col-xs-2 my-3 py-0" align="justify">
                    <p>
                        <b>A. (
                            @if ($opcaoEducacional->opcao_educacional == 1)
                                X
                            @endif
                         ) OPÇÃO PELO ENSINO HÍBRIDO</b>. Nesta condição, declaro(amos) ciência de que o ano letivo
                        poderá ser desenvolvido com <b>atividades presenciais e/ou remotas</b>, com permissão expressa para
                        alteração de formato conforme a necessidade indicada pela escola CONTRATADA a partir das
                        determinações e recomendações dos órgãos públicos e aceito(amos) a aplicação dos protocolos
                        sanitários nas instalações escolares, ciente(s) dos riscos e implicações com a circulação de pessoas
                        devido à pandemia do novo coronavirus/COVID-19. Assumo(imos) o compromisso de colaborar no
                        combate à doença e afastar o(a) aluno(a) na ocorrência de febre ou sintomas de gripe até a
                        confirmação médica de cessação do risco de contágio.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-xs-2 my-3 py-0" align="justify">
                    <p>
                        <b>B. (
                            @if ($opcaoEducacional->opcao_educacional == 2)
                                X
                            @endif
                         ) OPÇÃO EXCLUSIVA PELO ENSINO REMOTO</b>. Nesta condição, declaro(amos) ciência de
                        que o ALUNO somente terá <b>atividades letivas não presenciais</b> enquanto durar a pandemia, e
                        aceito(amos) a realização de aulas, provas e exercícios por meio de plataforma digital.
                        Assumo(imos) o compromisso de prestar assistência doméstica ao(à) o aluno(a) incentivando(a) e
                        apoiando(a) para o progresso do aprendizado.

                    </p>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-xs-2 my-3 py-0" align="justify">
                    <p>
                        Em qualquer uma das opções, declaro(amos) ciência de que os serviços educacionais estão tendo
                        continuidade, e por isso são mantidas as condições financeiras combinadas na matrícula, e que o
                        acesso à internet doméstica e disponibilização de computador, tablet ou smartphone é
                        responsabilidade da família.
                    </p>
                    
                    <font size="3px">Este termo foi gravado por {{$opcaoEducacional->name}}, em {{ date('d/m/Y', strtotime($opcaoEducacional->data_hora))}}, utilizando autenticação de login e senha.  </font>
                </div>
            </div>

            <center>{{$unidadeEnsino->cidade_uf}}, {{date('d/m/Y', strtotime($opcaoEducacional->data_hora))}}.</center>

            <br>
            <table align="center">
                <tr>
                    <td width="300px">
                        <hr>
                        <b>
                            {{$opcaoEducacional->responsavel}} <br>                            
                            CPF: {{mascaraCpfCnpj('###.###.###-##', $opcaoEducacional->cpf)}}
                        </b>            
                    </td>
                    <td width="50px"></td>
                    <td width="300px">
                        <hr>            
                        <b>                                    
                            {{$unidadeEnsino->razao_social}} <br>
                            {{mascaraCpfCnpj('##.###.###/####-##', $unidadeEnsino->cnpj)}}
                            
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
                        CPF:                                         
                    </td>
                    <td width="50px"></td>
                    <td width="300px">
                        <br>
                        <hr>                                
                        Nome: <br>                                
                        CPF:                                         
                    </td>
                </tr>
            </table>
            
        </div>
        
    </div>
    @include('secretaria.paginas._partials.rodape_redeeduca')
   

    {{-- <footer class="footer">
        
        <div class="row mx-auto">
            
            <div class="col-sm-12 col-xs-2 ml-5 my-py-0 mx-auto" align="center">
                <font size="1px">CiberEduca - Plataforma de Gestão Escolar</font>
            </div>         
        </div>
        <div class="row mx-auto">
            <div class="col-sm-12 col-xs-2 ml-5 my-py-0 mx-auto" align="center">
                <font size="1px">CiberSys - Sistemas Inteligentes</font>
            </div>            
        </div>
    </footer>  --}}   
    
</body>
</html>
