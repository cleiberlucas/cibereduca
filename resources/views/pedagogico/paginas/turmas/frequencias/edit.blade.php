@extends('adminlte::page')



@section('title_postfix', ' Frequências')

@section('content_header')
    @foreach ($frequenciaAluno as $index => $frequencia)  
        <ol class="breadcrumb">        
            <li class="breadcrumb-item active" >
                <a href="#" class="">Pedagógico</a>
            </li>
            <li class="breadcrumb-item active" >
                <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
            </li>
            <li class="breadcrumb-item active" >
                <a href="{{route('turmas.frequencias', $frequencia->matricula->fk_id_turma)}}" class="">Frequências</a>
            </li>
            <li class="breadcrumb-item active" >
                <a href="{{route('turmas.frequencias.showaluno', [$frequencia->fk_id_periodo_letivo, $frequencia->fk_id_matricula])}}" class="">Aluno</a>                
            </li>
            <li class="breadcrumb-item active" >
                <a href="#" class="">Alterar</a>
            </li>
        </ol>
          
        <h4>Alterar frequência do Aluno(a): <strong>{{$frequencia->matricula->aluno->nome}}</strong></h4>            
        <h4>{{$frequencia->periodoLetivo->periodo_letivo}} - {{$frequencia->matricula->turma->nome_turma}} {{$frequencia->matricula->turma->tipoTurma->sub_nivel_ensino}} - {{$frequencia->matricula->turma->turno->descricao_turno}} </h4>
        <h5>Disciplina: <strong>{{$frequencia->disciplina->disciplina}}</strong></h5>
        <h5>Data da aula: <strong>{{date('d/m/Y', strtotime($frequencia->data_aula))}}</strong></h5>
                
        @include('admin.includes.alerts')
    @endforeach
@stop

@section('content')
    <form action="{{ route('turmas.frequencias.update', $frequencia->id_frequencia)}}" class="form" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" class="" name="fk_id_turma" value="{{$frequencia->matricula->fk_id_turma}}">
        <input type="hidden" class="" name="fk_id_periodo_letivo" value="{{$frequencia->fk_id_periodo_letivo}}">

        <div class="row">
            <div class="form-group col-sm-3 col-xs-2">
                <select name="fk_id_tipo_frequencia" id="" class="form-control" required>                                                                        
                    @foreach ($tiposFrequencia as $tipoFrequencia)
                        <option value="{{$tipoFrequencia->id_tipo_frequencia }}"
                            @if ($tipoFrequencia->id_tipo_frequencia == $frequencia->fk_id_tipo_frequencia)
                                selected="selected"
                            @endif
                            >                    
                            {{$tipoFrequencia->tipo_frequencia}}
                        </option>
                    @endforeach
                </select>
            </div> 

        </div>
        <div class="row">
            <div class="form-group col-sm-4 col-xs-2">    
                <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Enviar</button>            
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function(){
              $(".alert").slideDown(300).delay(5000).slideUp(900);
        });    
    </script>

@stop
