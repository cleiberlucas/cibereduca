
@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    
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

        @include('admin.includes.alerts')

        <form action="{{ route('turmas.relatorios.diarios.filtros')}}" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    Ano Letivo
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value="" selected></option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-3 col-xs-2">
                    Turma
                <select name="turma" id="turma" class="form-control" {{old('turma')}} required > 
                        <option value=""></option>
                    </select>
                </div>            
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-xs-2">
                    Mês
                    <select name="mes" id="mes" class="form-control" required>
                        <option value=""></option>
                    </select>
                </div>  

                <div class="form-group col-sm-3 col-xs-2">
                    Disciplina
                    <select name="disciplina" id="disciplina" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="row">
                <h5>Fichas de Frequências</h5>            
            </div>
            
            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_mensal_disciplina" value="freq_mensal_disciplina">
                    <label for="freq_mensal_disciplina" class="form-check-label">Ficha Mensal disciplina</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_mensal_branco" value="freq_mensal_branco">
                    <label for="freq_mensal_branco" class="form-check-label">Ficha Mensal disciplina - em branco</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_diaria" value="freq_diaria" disabled>
                    <label for="freq_diaria" class="form-check-label">Ficha Diária - todos alunos e disciplinas</label>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frequencia" id="freq_aluno" value="freq_aluno" disabled>
                    <label for="freq_aluno" class="form-check-label">Ficha Aluno - anual</label>
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
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
    
@stop