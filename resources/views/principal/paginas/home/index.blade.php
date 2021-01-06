@extends('adminlte::page')

@section('title_postfix', '')

@section('content_header')        
   
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="form-group col-sm-12 col-xs-2"> 
                <h3>Seja bem vindo ao Colégio Rede Educa Goiás</h3>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-8 col-xs-2"> 
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
                    @elseif(count($unidadesEnsino) < 1)                            
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
            @if (count($unidadesEnsino) > 1)   
                <div class="form-group col-sm-1 col-xs-2">                 
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i></button>
                </div>
            @endif                      
        </div>

        <div class="row card-body justify-content-center">         
            <img width="85%" height="85%" src="vendor/adminlte/dist/img/rede-banner4.png" alt="">            
        </div>
        <div class="row justify-content-center">     
            <img width="2%" src="vendor/cibersys/img/cubo_magico.gif" alt="">   
            CiberSys - Sistemas Inteligentes            
        </div>
    </div>
@stop
