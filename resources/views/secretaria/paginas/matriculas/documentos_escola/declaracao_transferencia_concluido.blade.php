
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="favicons/favicon.ico" >
    <title>{{$unidadeEnsino->nome_fantasia}}-Declaração Transferência   
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
            <div class="row">
                <div class="form-group col-sm-11 col-xs-2 my-5 py-0" >  
                    <br><br><br>                                                          
                    <h4><center><strong> DECLARAÇÃO DE TRANSFERÊNCIA </strong></center></h4>
                    <br>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-sm-11 col-xs-2 my-0 py-0" align="justify">
                    <p>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Declaramos para os devidos fins e a pedido da parte interessada, que o(a) aluno(a) <b>{{$matricula->aluno->nome}}</b>,
                        filho(a) de {{$matricula->aluno->mae}}
                        @if (strlen($matricula->aluno->pai) > 2)
                            e {{$matricula->aluno->pai}},
                        @else
                        , 
                        @endif
                        nascido(a) no dia {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}},
                        concluiu o <b>{{$matricula->turma->tipoTurma->tipo_turma}}</b> do(a) {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} 
                        no ano de {{$anoLetivo->ano}} e requereu sua transferência na presente data, estando apto(a) a cursar o(a) {{$aptoCurso}} do {{$nivelEnsino}}.
                    </p>                    
                </div>
            </div>
            <div class="row"></div>
                <div class="form-group col-sm-11 col-xs-2 my-3 py-0" align="justify">
                    <p>
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Diretoria do {{$unidadeEnsino->razao_social}} em {{$unidadeEnsino->cidade_uf}}, aos {{date('d')}} dias do mês de {{nomeMesMinusculo(date('m'))}} de {{date('Y')}}.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-11 col-xs-2 my-5" align="justify">                    
                    <p align="center">
                    _________________________________________
                    <br>
                    {{$unidadeEnsino->nome_assinatura}}
                    <br>
                    {{$unidadeEnsino->cargo_assinatura}}
                    </p>
                </div>                    
            </div>
            <div class="row">
                <div class="form-group  col-sm-3 col-xs-2 mt-5" align="justify">
                    <div class="visible-print text-center">
                        {!! QrCode::size(100)->color(11,85,11)->generate($url_qrcode); !!}                    
                    </div>                
                </div>
            </div>
            
            <div class="row">
                <div class="form-group  col-sm-10 col-xs-2 ml-5">
                    <font size="2px">            
                    Verifique a autenticidade deste documento em {{$url_texto}}
                    <br>
                    Documento gerado em {{date('d/m/Y H:i:s')}} - Código de Validação: {{$codigoValidacao}}
                </font>
                </div>
            </div>
        
        </div>
        
    </div>
    @include('secretaria.paginas._partials.rodape_redeeduca')
    
</body>
</html>
