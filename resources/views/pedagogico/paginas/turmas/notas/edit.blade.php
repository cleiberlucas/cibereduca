@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Notas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.index.notas')}} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.notas', $notaAluno->matricula->fk_id_turma)}}" class="">Notas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.notas.showaluno', [$notaAluno->fk_id_matricula])}}" class="">Aluno</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">Alterar Nota</a>
        </li>
        
    </ol>
          
        <h3>Alterar nota do Aluno(a): <strong>{{$notaAluno->matricula->aluno->nome}}</strong></h3>            
        <h4>{{$notaAluno->avaliacao->periodoLetivo->periodo_letivo}} - {{$notaAluno->matricula->turma->nome_turma}} {{$notaAluno->matricula->turma->tipoTurma->sub_nivel_ensino}} - {{$notaAluno->matricula->turma->turno->descricao_turno}} </h4>
        <h5>Disciplina: <strong>{{$notaAluno->avaliacao->disciplina->disciplina}}</strong></h5>
        <h5>Avaliação: <strong>{{$notaAluno->avaliacao->tipoAvaliacao->tipo_avaliacao}} - valor {{number_format($notaAluno->avaliacao->valor_avaliacao, 2, ',', '.')}}</strong></h5>
                
@stop

@section('content')
    <form action="{{ route('turmas.nota.update', $notaAluno->id_nota_avaliacao)}}" class="form" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-sm-2 col-xs-2">
                <label for="">Valor nota</label>                
                <input type="number" name="nota" id="nota" step="0.010" min=0 max={{$notaAluno->avaliacao->valor_avaliacao}} id="" value="{{$notaAluno->nota}}" class="form-control">
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
