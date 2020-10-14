
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

        <form action="{{ route('turmas.relatorios.diarios.filtros')}}" id="diario" name="diario" class="form" method="POST">
            @csrf            
            <div class="row">
                <div class="form-group col-sm-2 col-xs-1">
                    Ano Letivo
                    <select class="form-control" name="anoLetivo" id="anoLetivo" required>
                        <option value="0">Selecione</option>
                        @foreach ($anosLetivos as $anoLetivo)
                            <option value="{{$anoLetivo->id_ano_letivo}}">{{$anoLetivo->ano}}</option>
                            
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-4 col-xs-2">
                    Turma
                    <select name="turma" id="turma" class="form-control"  required > 
                        <option value=""></option>
                    </select>
                </div>    
                         
            </div>
            <div class="row">
                <div class="form-group col-sm-2 col-xs-2">
                    Mês
                    <select name="mes" id="mes" class="form-control" >
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

            {{-- Abas  --}}
            <ul class="nav nav-tabs nav-pills " role="tablist">  
                <li role="presentation" class="nav-item">
                    <a class="nav-link " href="#conteudo" aria-controls="conteudo" role="tab" data-toggle="tab">Conteúdos Lecionados</a>                    
                </li>

                <li role="presentation" class="nav-item">
                    <a class="nav-link " href="#frequencia" aria-controls="frequencia" role="tab" data-toggle="tab">Frequências</a>                    
                </li> 

                <li role="presentation" class="nav-item" >
                    <a class="nav-link " href="#boletim" aria-controls="boletim" role="tab" data-toggle="tab" >Boletins</a>                    
                </li>
                
                <li role="presentation" class="nav-item">
                    <a class="nav-link " href="#bimestral" aria-controls="bimestral" role="tab" data-toggle="tab">Resultados Bimestrais</a>                    
                </li>

                <li role="presentation" class="nav-item">
                    <a class="nav-link " href="#anual" aria-controls="anual" role="tab" data-toggle="tab">Resultados Anuais</a>                    
                </li>
            </ul>

            <div class="tab-content">
                {{-- Conteúdos lecionados --}}            
                <div role="tabpanel" class="tab-pane" id="conteudo"> 
                    <div class="form-group col-sm-3 col-xs-2">
                        <font color="blue">Período Letivo</font>                                
                        <select name="conteudo_periodo" id="conteudo_periodo" class="form-control"> 
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="conteudo_bimestral_disciplina" value="conteudo_bimestral_disciplina" >
                            <label for="conteudo_bimestral_disciplina" class="form-check-label">Conteúdo Lecionado</label>
                            <br>
                            <small>(uma disciplina)</small>
                        </div>
                    </div>
                </div>

                {{-- Frequências --}}            
                <div role="tabpanel" class="tab-pane" id="frequencia"> 
                    <font color="blue">
                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="freq_mensal_disciplina" value="freq_mensal_disciplina" >
                            <label for="freq_mensal_disciplina" class="form-check-label">Ficha Mensal disciplina</label>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="freq_mensal_branco" value="freq_mensal_branco" >
                            <label for="freq_mensal_branco" class="form-check-label">Ficha Mensal disciplina - em branco</label>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="freq_diaria" value="freq_diaria" disabled>
                            <label for="freq_diaria" class="form-check-label">Ficha Diária - todos alunos e disciplinas</label>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="freq_aluno" value="freq_aluno" disabled>
                            <label for="freq_aluno" class="form-check-label">Ficha Aluno - anual</label>
                        </div>
                    </div>
                    </font>
                </div>

                {{-- Boletins --}}   
                        
                    <div role="tabpanel" class="tab-pane " id="boletim"> 
                        <font color="green"> 
                        <div class="row my-3 ml-5">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_relatorio" id="boletim_aluno" value="boletim_aluno" required>
                                <label for="boletim_aluno" class="form-check-label">Boletim Aluno</label>
                            </div>
                            <div class="form-group ml-3 col-sm-4 col-xs-2">
                                Aluno
                                <select name="fk_id_matricula" id="fk_id_matricula" class="form-control"> 
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row my-3 ml-5">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_relatorio" id="boletim_turma" value="boletim_turma" >
                                <label for="boletim_turma" class="form-check-label">Boletins Turma</label>
                            </div>
                        </div>                    
                    </font>
                    </div>
                

                {{-- Relatórios bimestrais --}}
                <div role="tabpanel" class="tab-pane" id="bimestral">   
                    <div class="row">
                        
                            <div class="form-group col-sm-3 col-xs-2">
                                <font color="blue">Período Letivo</font>                                
                                <select name="periodo" id="periodo" class="form-control"> 
                                    <option value=""></option>
                                </select>
                            </div>
                        
                    </div>

                   {{--  <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="aprendizagem" value="aprendizagem" >
                            <label for="aprendizagem" class="form-check-label">Acompanhamento de Aprendizagem</label>
                            <br>
                            <small>(uma disciplina)</small>
                        </div>
                    </div> --}}

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="avaliacoes_bimestre" value="avaliacoes_bimestre" >
                            <label for="avaliacoes_bimestre" class="form-check-label">Resultado Bimestral</label>
                            <br>
                            <small>(avaliações de uma disciplina)</small>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="rendimento_escolar" value="rendimento_escolar">
                            <label for="rendimento_escolar" class="form-check-label">Rendimento Escolar</label>
                            <br>
                            <small>(todas disciplinas)</small>
                        </div>
                    </div>

                    {{-- <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="resultado_bimestre_II" value="resultado_bimestre_II" disabled>
                            <label for="resultado_bimestre_II" class="form-check-label">Resultado Bimestral II</label>
                            <br>
                            <small>(todas disciplinas)</small>
                        </div>
                    </div> --}}

                </div>

                {{-- Relatórios anuais --}}
                <div role="tabpanel" class="tab-pane" id="anual">                     
                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="resultado_semestre" value="resultado_semestre" disabled >
                            <label for="resultado_semestre" class="form-check-label">Resultado Semestral</label>
                            <br>
                            <small>(uma disciplina)</small>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="resultado_anual_1" value="resultado_anual_1" disabled>
                            <label for="resultado_anual_1" class="form-check-label">Resultado Anual 1</label>
                            <br>
                            <small>(todas disciplinas)</small>
                        </div>
                    </div>

                    <div class="row my-3 ml-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo_relatorio" id="resultado_anual_2" value="resultado_anual_2" disabled>
                            <label for="resultado_anual_2" class="form-check-label">Resultado Anual 2</label>
                            <br>
                            <small>(uma disciplina)</small>
                        </div>
                    </div>
                </div>

            </div>

            <hr>
            <div class="row ml-5">
                <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="{!!asset('/js/populaTurmas.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaAlunosTurma.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaMeses.js')!!}"></script>
    {{-- <script type="text/javascript" src="{!!asset('/js/populaPeriodos.js')!!}"></script> --}}
    <script type="text/javascript" src="{!!asset('/js/populaDisciplinas.js')!!}"></script>
    <script type="text/javascript" src="{!!asset('/js/populaPeriodosLetivos.js')!!}"></script>
    
    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });  
    </script>
    
@stop