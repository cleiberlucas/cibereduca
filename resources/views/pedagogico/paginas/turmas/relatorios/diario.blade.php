
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
        <div class="row">
            <div class="form-group col-sm-2 col-xs-1">
                Ano Letivo
                <select class="form-control" name="anoLetivo" id="anoLetivo" onclick="getTurmasAnoLetivo()">
                    <option value="0">Ano</option>
                    @foreach ($anosLetivos as $anoLetivo)
                        <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                        
                    @endforeach
                </select>
            </div>

            <div class="form-group col-sm-3 col-xs-2">
                Turma
                <select name="turma" id="turma" class="form-control">
                    <option value="0">Selecione a Turma</option>
                </select>

            </div>
        </div>
    </div>
    
    <script type="text/javascript" src="{!!asset('/js/populaCombos.js')!!}"></script>
    
@stop