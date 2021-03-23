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
        /* background-image: url('vendor/adminlte/dist/img/portal_aluno_fundo.png') ; */
       /*  background-size: cover;        
        /* height: 70vh; */
        /* opacity: 0.65; */
        /* background-repeat: no-repeat;  */
        /* background-size: : 100%; */

        /* background-repeat: no-repeat;
        background-size:auto 100%;
        bottom: 0;
        color: black;
        left: 0;
        /* overflow: auto; */
        /* padding: 3em; */
        /* position: absolute; */
        /*right: 0;
        text-align: center;
        top: 0;
        background-size: cover;
         */

        background-image: url('vendor/adminlte/dist/img/portal_aluno_fundo.png') ; 
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        
        
    }
/* 
    @media (min-width: sm) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/portal_aluno_fundo.png');
        }
    }

    @media (min-width: md) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/portal_aluno_fundo.png');
        }
    }

    @media (min-width: lg) {
        .container-portal{
            background-image: url('vendor/adminlte/dist/img/portal_aluno_fundo.png');
        }
    } */
/* 
    .imagem-botoes{
        opacity: 1; !important
    }

    .div-pai:before {
        content: '';
        position: absolute;
        top: 600px; bottom: 0;
        left: 0; right: 0;
        background: white;
        opacity: 0.5;
    }

    .overlay {
        content: '';
        position: absolute;
        top:730px; bottom: 80px;
        left: 0; right: 0;
        background: white;

        filter: alpha(opacity=30); /* IE5-8 */
   /*     opacity: 0.65; /* IE9+ e todos browsers modernos */
    /*} */
</style>
<script src="js/app.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<body>
    
    <div class="container-fluid container-portal">       
        
        <div class="row justify-content-md-center " align="center">
            <div class="col-sm-12 col-xs-2 col-md-6 text-white ">
            <h5>Seja bem vindo(a) ao Colégio Rede Educa</h5>
            </div>
        </div>
        <div class="row justify-content-md-center" style="float: right">            
            <div class="col-sm-12 col-xs-2  bg-primary text-white" align="right">
           {{--      <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> --}}
                        @if (isset($pessoa->nome))
                            {{$pessoa->nome}}                                                            
                        @else
                            <font color=red>Login incompleto. Favor entrar em contato com a secretaria da escola.</font>                            
                        @endif                   
                        <br>
                        <a href="{{route('users.editsenha')}}" class="btn-sm btn-outline-light"> Alterar Senha</a> 
                        <br>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-sm btn-outline-light">Sair</a>
                        <form id="logout-form" action="logout" method="POST" style="display: none;">
                            @csrf
                        </form>
                        
					{{-- </a>
					<div class="dropdown-menu" >
                        <a class="dropdown-item" href="">Alterar Senha</a>						  
						<a class="dropdown-item" href="">Sair</a>						  
					</div>
                </li>
            </ul> --}}
            </div>
            
        </div>    
        <div class="row mt-0 pt-0 ">
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
        
        <br><br><br><br><br><br>
        <br><br><br><br><br><br>
        
        <div class="row justify-content-md-center mt-5 pt-5 " >         
            <div class="form-group col-sm-6 col-xs-2 " align="center">               
                <a href="{{route('portal.indexrendimento')}}" data-content="Boletins e notas." data-toggle="popover" data-trigger="hover">
                    <img width="40%" src="vendor/adminlte/dist/img/rendimento_aluno.png" alt="">
                </a>
            </div>
            <div class="form-group col-sm-6 col-xs-2" align="center">
                <a href="{{route('portal.indexdeclaracoes')}}" data-content="Declarações" data-toggle="popover" data-trigger="hover">        
                    <img width="40%" src="vendor/adminlte/dist/img/declaracoes.png" alt="">
                </a>
            </div>
        </div>
        <div class="row justify-content-md-center" align="center">                     
            <div class="form-group col-sm-6 col-xs-2 " align="center">                
                <a href="{{route('portal.indexfinanceiro')}}" data-content="Mensalidades" data-toggle="popover" data-trigger="hover">
                    <img width="40%" src="vendor/adminlte/dist/img/financeiro_new.png" alt="">
                </a>
            </div>
            <div class="form-group col-sm-6 col-xs-2" align="center">
                <a href="{{route('portal.indexoutros')}}" data-content="Contrato, opção educacional." data-toggle="popover" data-trigger="hover" >
                    <img width="40%" src="vendor/adminlte/dist/img/outros.png" alt="">
                </a>
            </div>            
        </div>        
        
        <div class="row justify-content-center">     
            <div class="form-group col-sm-12 col-xs-2" align="center">
                <img width="5%" src="vendor/cibersys/img/cubo_magico.gif" alt="">   
                <strong>CiberSys - Sistemas Inteligentes</strong>
            </div>
        </div>    
    </div> {{-- fim container-fluid --}}
 </body>
 <script>
      $('[data-toggle="popover"]').popover();  
 </script>
</html>
