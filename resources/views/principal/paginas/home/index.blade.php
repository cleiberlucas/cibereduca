@extends('adminlte::page')

<section></section>

@section('title_postfix', '')

@section('content_header')        
   
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="form-group col-sm-4 col-xs-2"> 
                <h2>Colégio Rede Educa Goiás</h2>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4 col-xs-2"> 
                <form action="{{ route('home.define')}}" class="form" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Caso tenha só uma unidade de ensino, seta p sessão automaticamente --}}
                    @if(count($unidadesEnsino) == 1)
                        <?php
                            session()->forget('id_unidade_ensino'); 
                            foreach ($unidadesEnsino as $unidadeEnsino) {
                                session()->put('id_unidade_ensino', $unidadeEnsino->id_unidade_ensino);    
                            }                            
                        ?>
                    @endif
                    @if (count($unidadesEnsino) > 0)        
                        <select name="unidadeensino" id="unidadeensino" class="form-control">
                            @foreach ($unidadesEnsino as $unidadeEnsino)
                                <option value="{{$unidadeEnsino->id_unidade_ensino}}">{{$unidadeEnsino->nome_fantasia}}</option>                                
                            @endforeach
                        </select>
                    @else
                        <h2> Não há Unidade de Ensino ativa para o seu usuário!</h2>
                    @endif                    
            </div>
            <div class="form-group col-sm-1 col-xs-2">                 
                <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i></button>

            </div>
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
