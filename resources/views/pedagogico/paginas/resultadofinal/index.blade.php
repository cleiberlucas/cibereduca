<?php
    
    ob_flush();
        
?>

@extends('adminlte::page')

@section('title_postfix', ' Resultado Final')

@section('content_header')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('resultadofinal.index.turmas')}} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Resultado Final</a>
        </li>
    </ol>
    <h4>Resultado Final {{$turma->tipoTurma->anoLetivo->ano}} </h4>
    <h5>{{$turma->nome_turma}} {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}} - {{$turma->turno->descricao_turno}} </h5>    
    
@stop

@section('content')
    
    <div class="container-fluid">
        <h6>Navegue pelas abas das disciplinas para ver as médias e faltas.</h6>
        
        <div>@include('admin.includes.alerts')</div>

        {{-- Abas das disciplinas: Todas a grade curricular da turma --}}
        <ul class="nav nav-tabs nav-pills nav-fill justify-content-center" role="tablist">
            @foreach ($disciplinasTurma as $disciplinaTurma)                
                <li role="presentation" class="nav-item ">
                    <a class="nav-link" href="#{{$disciplinaTurma->fk_id_disciplina}}" aria-controls="{{$disciplinaTurma->fk_id_disciplina}}" role="tab" data-toggle="tab">{{$disciplinaTurma->disciplina}}</a>
                </li>                        
            @endforeach
        </ul> 

        {{-- Conteúdo abas disciplinas --}}
        <div class="tab-content">
            @foreach ($disciplinasTurma as $disciplinaTurma)

                <div role="tabpanel" 
                    @if (isset($selectDisciplina) && $selectDisciplina == $disciplinaTurma->fk_id_disciplina)
                        class="tab-pane active"         
                    @else
                        class="tab-pane" 
                    @endif
                    id="{{$disciplinaTurma->fk_id_disciplina}}">
                    
                    {{-- Listando alunos para lançamento das notas --}}
                    <div class="table-responsive">
                        {{-- Listagem de alunos e notas --}}                                              
                            <br>
                            <div class="row">
                                
                                <div class="form-group col-sm-5 col-xs-1" align="center">
                                    <br>
                                    <h5><strong>Resultado Anual: <font color="green">{{$disciplinaTurma->disciplina}}</font></strong></h5>
                                </div>
                                
                            </div>                                        
                            
                            @foreach ($turmaMatriculas as $index => $turmaMatricula)

                                {{-- Cabeçalho da tabela --}}
                                @if ($index == 0)
                                    <div class="row">
                                        <div class="form-group col-sm-1 col-xs-1">
                                            <strong>N°</strong>
                                        </div>
                                        <div class="form-group col-sm-4 col-xs-2">
                                            <strong>Nome do(a) Aluno(a)</strong>                                                        
                                        </div>   
                                        <div class="form-group col-sm-4 col-xs-2">
                                            <strong>Média anual &nbsp;&nbsp;&nbsp; Total Faltas</strong>                                                        
                                        </div>                                           
                                          
                                    </div>  
                                @endif {{-- fim cabeçalho tabela --}}
                                
                                <input type="hidden" name="fk_id_matricula[]" value="{{$turmaMatricula->id_matricula}}">                                                            
                                        
                                <div class="row">
                                    <div class="form-group col-sm-1 col-xs-2">
                                        <strong>{{$index+1}}  </strong>                                                                  
                                    </div>
                                    <div class="form-group col-sm-4 col-xs-2">                                                                                            
                                        {{$turmaMatricula->nome}}
                                    </div>       
                                    <div class="form-group col-sm-4 col-xs-2">
                                        <?php 
                                            foreach ($resultados as $resultado){
                                                if ($resultado->fk_id_matricula == $turmaMatricula->id_matricula and $resultado->fk_id_disciplina == $disciplinaTurma->fk_id_disciplina){                                                
                                                    echo number_format($resultado->media/4, 1, ',', '.');
                                                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    echo $resultado->faltas;
                                            
                                                    break;                                                
                                                }
                                            }
                                        ?>
                                    </div>   

                                </div>
                            @endforeach {{-- fim listagem alunos --}}                           
                            
                        </form>                                            
                    </div>                                    
                </div>
            @endforeach {{-- fim aba disciplinas --}}
        </div>
    </div>

    <div class="container">
        <br>
        <h4>Lançamento do Resultado Final</h4>
        <br>
        <div class="table-responsive">
            <form action="{{ route('resultadofinal.store', 1)}}" method="POST">
                @csrf                                         
                <input type="hidden" name="fk_id_disciplina" value={{$disciplinaTurma->fk_id_disciplina}}>
                <input type="hidden" name="fk_id_user" value={{Auth::id()}}>         
                <input type="hidden" name="fk_id_turma" value={{$id_turma}}>     
                
                 <div class="row">
                    <div class="form-group col-sm-10 col-xs-1" align="left">
                        <font color='green'>
                            Utilize os ícones <i class="fas fa-edit"></i> para alterar o resultado final de um aluno.
                        </font>
                    </div>
                </div>
                            

                @foreach ($turmaMatriculas as $index => $turmaMatricula)

                    {{-- Cabeçalho da tabela --}}
                    @if ($index == 0)
                        <div class="row">
                            <div class="form-group col-sm-1 col-xs-1">
                                <strong>N°</strong>
                            </div>
                            <div class="form-group col-sm-4 col-xs-2">
                                <strong>Nome do(a) Aluno(a)</strong>                                                        
                            </div>                           
                            <div class="form-group col-sm-3 col-xs-2">
                                <strong>Resultado Final</strong>                                                        
                            </div>   
                                
                        </div>  
                    @endif {{-- fim cabeçalho tabela --}}
                    
                    <input type="hidden" name="fk_id_matricula[]" value="{{$turmaMatricula->id_matricula}}">                                                            
                            
                    <div class="row">
                        <div class="form-group col-sm-1 col-xs-2">
                            <strong>{{$index+1}}  </strong>                                                                  
                        </div>
                        <div class="form-group col-sm-4 col-xs-2">                                                    
                            <a href="{{route('resultadofinal.edit', [$turmaMatricula->id_matricula])}}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                            {{$turmaMatricula->nome}}
                        </div>       
                        
                        <div class="form-group col-sm-3 col-xs-2">                        
                            <select name="fk_id_tipo_resultado_final[]" id="" class="form-control" required>        
                                <option value=""></option>
                                @foreach ($tiposResultados as $tipoResultado)
                                    <option value="{{$tipoResultado->id_tipo_resultado_final }} "
                                        <?php
                                            $resultadoGravado = false;
                                            foreach ($resultadosFinais as $key => $resultadoFinal) {
                                                if ($resultadoFinal->fk_id_matricula == $turmaMatricula->id_matricula and $resultadoFinal->fk_id_tipo_resultado_final == $tipoResultado->id_tipo_resultado_final){
                                                    echo 'selected="selected"';
                                                    $resultadoGravado = true;
                                                    break;
                                                }
                                            }                                        
                                            if ($resultadoGravado == false and $tipoResultado->padrao == 1)
                                                echo 'selected="selected"';
                                        ?>
                                    >{{$tipoResultado->tipo_resultado_final}}
                                    
                                    </option>                                
                                @endforeach

                            </select>
                        </div>  

                    </div>
                 @endforeach {{-- fim listagem alunos --}}        
            
                <div class="form-group col-sm-2 col-xs-2">
                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>
                </div>
            </form>        
        </div>
    </div>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(300);
        });    
    </script>
@stop
