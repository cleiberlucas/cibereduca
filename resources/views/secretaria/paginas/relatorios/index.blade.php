@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Relatórios')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="# " class="">Secretaria</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Relatórios</a>
        </li>
    </ol>
    <div class="row">
        <h4>Relatórios Secretaria</h4>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
@stop
    
@section('content')
    <div class="container-fluid">
        @include('admin.includes.alerts')

        <form action="{{ route('secretaria.relatorios.filtros')}}" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    Ano Letivo
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value=""></option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-5 col-xs-2">
                    Turma
                    <select name="turma" id="turma" class="form-control"> 
                        <option value=""></option>
                    </select>
                </div>            
            </div>
            <hr>

            <div class="row">
                <h5>Tipos de relatórios</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="alunos_turma" value="alunos_turma" required>
                    <label for="alunos_turma" class="form-check-label">Alunos de uma Turma</label>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="todas_matriculas" value="todas_matriculas">
                    <label for="todas_matriculas" class="form-check-label">Todos alunos matriculados</label>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo_relatorio" id="todos_alunos" value="todos_alunos" disabled>
                    <label for="todos_alunos" class="form-check-label">Todos alunos cadastrados</label>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="form-group col-sm-3 col-xs-2">
                    Ordenação
                    <select name="ordem" id="ordem" class="form-control" required > 
                        <option value=""></option>
                        <option value="nome">Nome</option>
                        <option value="data_nascimento">Data Nascimento</option>                    
                        <option value="nome_turma">Turma</option>
                        <option value="name">Cadastrado por</option>
                    </select>
                </div> 
            </div>

            <hr>
            <div class="row">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>

        </form>

    </div>
    
    <script type="text/javascript" src="{!!asset('/js/populaTurmas.js')!!}"></script>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
@stop
