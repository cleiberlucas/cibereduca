@extends('adminlte::page')

@section('title_postfix', ' Notas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('resultadofinal.index.turmas')}} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('resultadofinal.index', $matricula->fk_id_turma) }}" class="">Resultado Final</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#" class="">Alterar</a>
        </li>
    </ol>
    <br>
    <h4>Alterar Resultado Final do Aluno(a): <strong>{{$matricula->aluno->nome}}</strong></h4>            
    <h5>Ano Letivo {{$matricula->turma->tipoTurma->anoLetivo->ano}}</h5>
    <h5>{{$matricula->turma->nome_turma}} {{$matricula->turma->tipoTurma->sub_nivel_ensino}} - {{$matricula->turma->turno->descricao_turno}} </h5>
                
@stop

@section('content')
    <form action="{{ route('resultadofinal.update', $resultadoFinal->id_resultado_final)}}" class="form" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" class="" id="fk_id_user" name="fk_id_user" value="{{Auth::id()}}">  
        <input type="hidden" class="" name="id_turma" value="{{$matricula->fk_id_turma}}">

        <div class="row">
            <div class="form-group col-sm-2 col-xs-2">
                <label for="">Resultado Final</label>                
                <select name="fk_id_tipo_resultado_final" id="" class="form-control" required>        
                    <option value=""></option>
                    @foreach ($tiposResultados as $tipoResultado)
                        <option value="{{$tipoResultado->id_tipo_resultado_final }} "
                            @if ($tipoResultado->id_tipo_resultado_final == $resultadoFinal->fk_id_tipo_resultado_final)
                                selected="selected"
                            @endif
                        >{{$tipoResultado->tipo_resultado_final}}
                        
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
        document.getElementById("nota").addEventListener("change", function(){
            this.value = parseFloat(this.value).toFixed(2);
        });
    </script>

@stop
