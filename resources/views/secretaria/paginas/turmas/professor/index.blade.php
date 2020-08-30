@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Turmas')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmas.index') }} " class="">Turmas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmasprofessor', $turma->id_turma) }}" > Professores</i></a>
    </li>
</ol>
    
    <div class="row"> 
        <div class="form-group col-sm-9 col-sx-2">
            <h5>Vincular Disciplinas X Professores</h5>    
        </div>        
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{route('turmasprofessor.store') }}" class="form" method="POST">            
                @csrf           
            
                <h5>Ano Letivo - {{$turma->tipoTurma->anoLetivo->ano}}</h5>
                <h5>{{$turma->nome_turma}} - {{$turma->turno->descricao_turno}}</h5>
        </div>
        
        @include('admin.includes.alerts')
        @csrf
        

            <input type="hidden" name="fk_id_turma" value="{{$turma->id_turma}}">
            <input type="hidden" name="situacao_disciplina_professor" value="1">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>                        
                        <th >Disciplina</th>                        
                        <th>Professor</th>                    
                        <th>Ações</th>
                    </thead>
                    <tbody>
                        @foreach ($gradeCurricular as $index => $disciplina)
                        <input type="hidden" name="fk_id_grade_curricular[{{$index}}]" value="{{$disciplina->id_grade_curricular}}">
                            <tr>
                                <td>
                                    {{$disciplina->disciplina}}
                                </td>
                                <td>
                                    <?php $achou_prof = false;
                                        $id_turma_prof = 0;
                                    ?>
                                    @foreach ($turmaProfessores as $turmaProfessor)
                                        @if ($turmaProfessor->gradeCurricular->fk_id_disciplina == $disciplina->fk_id_disciplina and $turmaProfessor->situacao_disciplina_professor == 1)
                                            {{$turmaProfessor->professor->name}}
                                            <?php $achou_prof = true;
                                                $id_turma_prof = $turmaProfessor->id_turma_disciplina_professor;
                                            ?>
                                            @break;
                                        @endif
                                    @endforeach

                                    <?php 
                                        if (!$achou_prof){                                        
                                            echo '<select name="fk_id_professor['.$index.']" id="fk_id_professor['.$index.']" class="form-control" >
                                                <option value=""></option>';
                                                foreach ($professores as $professor)
                                                    echo '<option value="'.$professor->id.'"> '.$professor->name.'</option>';
                                                
                                            echo '</select>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($achou_prof){
                                        echo  "<a href=\" ".route('turmasprofessor.edit', "$id_turma_prof")."  \"  class=\"btn btn-sm btn-outline-success\"><i class=\"fas fa-edit\"></i></a>";

                                        /* <a href="{{ route('turmas.edit', $turma->id_turma) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a> */

                                    }?>
                                    
                                </td>
                                
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="form-group col-sm-4 col-xs-6">     
                    <div>                          
                        <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
                    </div>
                </div>
            </div>

        </form>
    </div>

    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.js"></script>

<script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });    
</script>

@stop