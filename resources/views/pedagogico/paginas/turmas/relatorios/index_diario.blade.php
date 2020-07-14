
@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Relatórios - Diários</a>
        </li>
    </ol>
    <h4>Relatórios dos Diários</h4>
@stop

@section('content')
    <div class="container-fluid">
        <form action="{{ route('turmas.relatorios.diarios.filtros')}}" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    Ano Letivo
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value="0">Ano</option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-3 col-xs-2">
                    Turma
                    <select name="turma" id="turma" class="form-control" required > 
                        <option value="0">Selecione</option>
                    </select>
                </div>            
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-xs-2">
                    Mês
                    <select name="mes" id="mes" class="form-control">
                        <option value="0">Selecione</option>
                    </select>
                </div>  

                <div class="form-group col-sm-3 col-xs-2">
                    Disciplina
                    <select name="disciplina" id="disciplina" class="form-control">
                        <option value="0">Selecione</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="row">
                <h5>Fichas de Frequências</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_disciplina" value="freq_disciplina">
                    <label for="freq_disciplina" class="form-check-label">Ficha disciplina</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_branco" value="freq_branco">
                    <label for="freq_branco" class="form-check-label">Ficha disciplina - em branco</label>
                </div>
            </div>

            <hr>
            <div class="row">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </form>
    </div>
    
    <script type="text/javascript" src="{!!asset('/js/populaTurmas.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaMeses.js')!!}"></script>
    {{-- <script type="text/javascript" src="{!!asset('/js/populaPeriodos.js')!!}"></script> --}}
    <script type="text/javascript" src="{!!asset('/js/populaDisciplinas.js')!!}"></script>
    
    
@stop