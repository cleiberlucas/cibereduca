
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Declaração Cursando   
    </title> 
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        
</head>
<style>
    html {
        height: 100%;
    }

    body, .container {
        height: 100%;
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
    

    .footer {
    position:absolute;
    bottom:0;
    width:100%;
}
   /*  table.report-container {
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
    } */
</style>

<body>
    <div class="container-fluid">        
        @include('secretaria.paginas._partials.cabecalho')
                
        <div class="row">
            <div class="form-group col-sm-11 col-xs-2 my-5 py-0" >  
                <br><br><br>                                                          
                <h4><center><strong> DECLARAÇÃO DE CURSANDO </strong></center></h4>
                <br>
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-sm-11 col-xs-2 my-0 py-0" align="justify">
                <p>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp A Diretora do {{$unidadeEnsino->razao_social}}, entidade sediada em Formosa-GO, declara que <b>{{$matricula->aluno->nome}}</b>, 
                nascido(a) em {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}}, filho(a) de {{$matricula->aluno->pai}}
                @if (strlen($matricula->aluno->mae) > 2)
                    e {{$matricula->aluno->mae}},
                @else
                ,
                @endif
                está matriculado(a) e cursando o(a) <b>{{$matricula->turma->tipoTurma->tipo_turma}}</b> do(a) {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                no {{$unidadeEnsino->razao_social}}.                      
                </p>
            </div>
        </div>
        <div class="row"></div>
            <div class="form-group col-sm-11 col-xs-2 my-3 py-0" align="justify">
                <p>
                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Diretoria do {{$unidadeEnsino->razao_social}} em Formosa-GO, aos {{date('d')}} dias do mês de {{nomeMes(date('m'))}} de {{date('Y')}}.
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
            <div class=" col-sm-3 col-xs-2 my-5" align="justify">
                <div class="visible-print text-center">
                    {!! QrCode::size(100)->color(11,85,11)->generate($url_qrcode); !!}                    
                </div>                
            </div>
        </div>
        
            <div class="row">
                <div class=" col-sm-10 col-xs-2 ml-5">
                    <font size="2px">            
                    Verifique a autenticidade deste documento em {{$url_texto}}
                    <br>
                    Documento gerado em {{date('d/m/Y H:m:i')}} - Código de Validação: {{$codigoValidacao}}
                </font>
                </div>
            </div>
        
        
        
      
        <div class="footer">
            <div class="row">
                
                <div class=" col-sm-10 col-xs-2 ml-5" align="center">
                    <font size="1px">
                    CiberEduca - Plataforma de Gestão Escolar   
                </font>
                </div>         
            </div>
            <div class="row">
                <div class=" col-sm-10 col-xs-2 ml-5" align="center">
                    <font size="1px">
                    CiberSys - Sistemas Inteligentes
                </font>
                </div>
            
            </div>
        </div>
    
    </div>
    
</body>
</html>