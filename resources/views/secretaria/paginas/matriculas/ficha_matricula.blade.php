
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$unidadeEnsino->nome_fantasia}}-Ficha Matrícula 
        @foreach ($matriculas as $index => $matricula)
            @if ($index == 0)
                {{$matricula->aluno->nome_aluno}}     
                @break;
            @endif            
        @endforeach
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
    <div class="container-fluid">    
        
        @include('secretaria.paginas._partials.cabecalho_redeeduca')
        <div class="content">               
            {{-- cabeçalho da ficha --}}
            @foreach ($matriculas as $index => $matricula)
                @if ($index == 0)
                    <hr>
                    
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0" >                                                            
                            <font size=4><center><strong> FICHA DE MATRÍCULA </strong></center>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-8 col-xs-2 my-0 py-0">
                            <strong>Nome do(a) aluno(a): {{$matricula->aluno->nome}}  </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-2 my-0 py-0">                    
                            Data de Nascimento: {{date('d/m/Y', strtotime($matricula->aluno->data_nascimento))}} &nbsp&nbsp&nbsp Sexo: {{$matricula->aluno->sexo->sexo}}
                        </div>
                        {{-- <div class="form-group col-sm-4 col-xs-2 my-0 py-0">
                            Cidade: &nbsp&nbsp&nbsp&nbsp UF:
                        </div> --}}
                            {{-- CPF: {{mascaraCpfCnpj('###.###.###-##', $matricula->aluno->cpf)}}    &nbsp&nbsp&nbsp Fone: {{mascaraTelefone("(##) #####-####",$matricula->aluno->telefone_1)}} --}}
                            
                        
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0">
                            Filiação: {{$matricula->aluno->pai}} 
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0">
                            e: {{$matricula->aluno->mae}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0">
                            Endereço: {{$matricula->responsavel->endereco->endereco ?? ''}} - {{$matricula->responsavel->endereco->complemento ?? ''}} &nbsp&nbsp&nbsp Nº {{$matricula->responsavel->endereco->numero ?? ''}}
                            <br>
                            Bairro: {{$matricula->responsavel->endereco->bairro ?? ''}} &nbsp&nbsp&nbspCidade: {{$matricula->responsavel->endereco->cidade->cidade ?? ''}}/{{$matricula->responsavel->endereco->cidade->estado->sigla ?? ''}}    
                            &nbsp&nbsp&nbsp CEP: 
                            @if (isset($matricula->responsavel->endereco->cep)) 
                                {{mascaraCEP('##.###-###', $matricula->responsavel->endereco->cep) ?? ''}}
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0">
                            Fones: 
                            @if (strlen($matricula->responsavel->telefone_1) == 10)
                                {{mascaraTelefone("(##) ####-####",$matricula->responsavel->telefone_1)}}
                            @else
                                {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_1)}}
                            @endif
                            e 
                            @if (strlen($matricula->responsavel->telefone_2) == 10)
                                {{mascaraTelefone("(##) ####-####",$matricula->responsavel->telefone_2)}}
                            @else
                                {{mascaraTelefone("(##) #####-####",$matricula->responsavel->telefone_2)}}
                            @endif
                            
                            {{-- &nbsp&nbsp&nbsp E-mail: {{$matricula->responsavel->email_1}} --}}
                        </div>
                    </div>

                    <div class="row" >
                        <div class="form-group col-sm-12 col-xs-2 my-0 py-0">
                            OBS:                            
                        </div>
                    </div>
               
                @endif
                @break;        

            @endforeach {{-- fim cabeçalho da ficha --}}
                        
        <hr>
        <font size="3">
        @foreach ($matriculas as $index => $matricula)                    
                                
            <div class="row">
                <div class="col-sm-12">
                    <table border="1">
                        <tr>
                            <td width="50%">
                                <table >
                                <tr>
                                    <td>
                                        Matrícula efetuada no(a) {{$matricula->turma->tipoTurma->tipo_turma}} do {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}},
                                        no ano de {{$matricula->turma->tipoTurma->anoLetivo->ano}}, por ter sido ______________________ na série anterior.                                
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <br>
                                        _________________________________________
                                        <br>
                                        Assinatura do Responsável pelo Aluno
                                        <br><br>
                                        _________________________________________
                                        <br>
                                        Assinatura do Diretor
                                    </td>
                                </tr>  
                            </table>  
                        
            
        @endforeach
                            </td>
                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>                            
                        </tr>    
                        
                        <tr>
                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   

                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   

                        </tr>

                        <tr>
                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   

                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   
                            
                        </tr>
                        <tr>
                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   

                            <td width="50%">
                                <table> 
                                    <tr>
                                        <td>
                                            Matrícula Efetuada no(a) ________________ do __________________________,
                                            no ano de ___________, por ter sido _________________ na série anterior.                                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Responsável pelo Aluno
                                            <br><br><br>
                                            _________________________________________
                                            <br>
                                            Assinatura do Diretor
                                        </td>
                                    </tr>     
                                </table>
                            </td>   
                            
                        </tr>

                    </table>        
                </div>
            </div>
        </font>    
    </div> 
    </div>                       
                {{-- &nbsp&nbsp&nbsp Série/Ano: {{$matricula->turma->nome_turma}} - {{$matricula->turma->tipoTurma->subNivelEnsino->sub_nivel_ensino ?? ''}} - {{$matricula->turma->tipoTurma->anoLetivo->ano ?? ''}} --}}
                   
    
    @include('secretaria.paginas._partials.rodape_redeeduca')
</body>
</html>