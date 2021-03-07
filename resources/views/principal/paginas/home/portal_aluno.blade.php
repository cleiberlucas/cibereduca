<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Portal do Aluno</title>
</head>
<style>  
    .container-portal{
        background-image: url('vendor/adminlte/dist/img/fundo_rede_educa.png');
        background-size: contain;        
        height: 70vh;
        background-repeat: no-repeat;
    }

    @media (min-width: sm) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/fundo_rede_educa.png');
        }
    }

    @media (min-width: md) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/fundo_rede_educa.png');
        }
    }

    @media (min-width: lg) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/fundo_rede_educa.png');
        }
    }

    .imagem-botoes{
        opacity: 1; !important
    }

    .div-pai:before {
        content: '';
        position: absolute;
        top: 0; bottom: 0;
        left: 0; right: 0;
        background: white;
        opacity: 0.3;
    }

    .overlay {
        content: '';
        position: absolute;
        top:130px; bottom: 80px;
        left: 0; right: 0;
        background: white;

        filter: alpha(opacity=30); /* IE5-8 */
        opacity: 0.65; /* IE9+ e todos browsers modernos */
    }
</style>
<script src="js/app.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<body>
    
    <div class="container-fluid">       
         
        <div class="row justify-content-md-center">
            {{-- <div class="form-group col-sm-3 col-xs-2"><img width="50%" height="" src="vendor/adminlte/dist/img/logo.png" alt=""></div> --}}
            <div class="form-group col-sm-9 col-xs-2" align="center"> 
                <h3><strong>Seja bem vindo ao Colégio Rede Educa Goiás</strong></h3>
                <br>
                <h2><strong>Portal do Aluno</strong></h2>
            </div>
        </div>    
        <div class="row my-0 py-0">
            <div class="form-group col-sm-12 col-xs-2"> 
                <form action="{{ route('home.define')}}" class="form" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Se o login cair, mostra link p login --}}
                    @if ( Auth::id() == null)
                        <a href="/login" class="btn btn-primary">Faça Login</a>                        
                    @endif
                  
                    {{-- Caso tenha só uma unidade de ensino, seta p sessão automaticamente --}}
                    @if(count($unidadesEnsino) == 1)
                        <?php
                            session()->forget('id_unidade_ensino'); 
                            foreach ($unidadesEnsino as $unidadeEnsino) {
                                session()->put('id_unidade_ensino', $unidadeEnsino->id_unidade_ensino);    
                            }                            
                        ?>
                    @elseif(count($unidadesEnsino) < 1 and Auth::id() != null)                             
                        <h4>O seu acesso ao sistema não foi concluído! </h4>
                        <h4>Favor entrar em contato com a secretaria do Colégio.</h4>
                    @endif
                  
                    {{-- Caso tenha mais de uma unidade, o usuário deve selecionar --}}
                    @if (count($unidadesEnsino) > 1)        
                        <select name="unidadeensino" id="unidadeensino" class="form-control">
                            @foreach ($unidadesEnsino as $unidadeEnsino)
                                <option value="{{$unidadeEnsino->id_unidade_ensino}}">{{$unidadeEnsino->nome_fantasia}}</option>                                
                            @endforeach
                        </select>                        
                    @endif      
                    
            </div>  
            @if (count($unidadesEnsino) > 1 )   
                <div class="form-group col-sm-1 col-xs-2">                 
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i></button>
                </div>
            @endif                      
        </div>
    </div>
    <div class="container-fluid container-portal div-pai">
        <div class="overlay"></div>
        <div class="row justify-content-md-center" >         
            <div class="col-sm-4 col-xs-2 imagem-botoes" align="center">
                <strong>Rendimento do Aluno</strong>
                <br>
                <a href="#" data-content="Boletins, notas e frequência." data-toggle="popover" data-trigger="hover">
                    <img width="20%" src="vendor/adminlte/dist/img/aluno.png" alt="">
                </a>
            </div>
            <div class="col-sm-4 col-xs-2" align="center">
                <strong>Declarações</strong>
                <br>
                <img width="40%"src="vendor/adminlte/dist/img/documentos.png" alt="">
            </div>
        </div>
        <div class="row justify-content-md-center" align="center">                     
            <div class="col-sm-4 col-xs-2 my-5" align="center">
                <strong>Financeiro</strong>
                <br>
                <img width="35%" src="vendor/adminlte/dist/img/financeiro.png" alt="">
            </div>
            <div class="col-sm-4 col-xs-2 my-5" align="center">
                <strong>Outros</strong>
                <br>
                <a href="#" data-content="Contrato, opção educacional." data-toggle="popover" data-trigger="hover">
                    <img width="30%"src="vendor/adminlte/dist/img/ponteiro_azul.png" alt="">
                </a>
            </div>            
        </div>        
        <div class="row justify-content-center">     
            <div class="col-sm-12 col-xs-2" align="center">
                <img width="5%" src="vendor/cibersys/img/cubo_magico.gif" alt="">   
                <strong>CiberSys - Sistemas Inteligentes</strong>
            </div>
        </div>    
    </div> 
 </body>
 <script>
      $('[data-toggle="popover"]').popover();  
 </script>
</html>
