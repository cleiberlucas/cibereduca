@extends('adminlte::page')

<section></section>

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
            <a href="{{route('turmas.frequencias', $frequencia->turmaPeriodoLetivo->fk_id_turma)}}" class="">Frequências</a>
            </li>
            <li class="breadcrumb-item active" >
                <a href="{{route('turmas.frequencias.showaluno', [$frequencia->fk_id_turma_periodo_letivo, $frequencia->fk_id_matricula])}}" class="">Aluno</a>
                
            </li>
            <li class="breadcrumb-item active" >
                <a href="#" class="">Alterar</a>
            </li>
        </ol>
          
        <h2>Alterar frequência do Aluno(a): <strong>{{$frequencia->matricula->aluno->nome}}</strong></h2>            
        <h3>{{$frequencia->turmaPeriodoLetivo->periodoLetivo->periodo_letivo}} - {{$frequencia->turmaPeriodoLetivo->turma->nome_turma}} {{$frequencia->turmaPeriodoLetivo->turma->tipoTurma->sub_nivel_ensino}} - {{$frequencia->turmaPeriodoLetivo->turma->turno->descricao_turno}} </h3>
        <h3>Disciplina: <strong>{{$frequencia->disciplina->disciplina}}</strong></h3>
        <h4>Data da aula: <strong>{{date('d/m/Y', strtotime($frequencia->data_aula))}}</strong></h4>
                
    @endforeach
@stop

@section('content')
    <form action="{{ route('turmas.frequencia.update', $frequencia->id_frequencia)}}" class="form" method="POST">
        @csrf
        @method('PUT')
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

@stop
