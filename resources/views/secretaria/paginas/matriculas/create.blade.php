@extends('adminlte::page')

@section('title_postfix', ' Matrículas')

@section('content_header')
    
    <div class="row">
        <div class="col-sm-8 col-xs-6">
            <h1><strong>Matricular </strong> Aluno {{$turma->ano}} - 
            Turma: {{$turma->nome_turma}} - {{$turma->descricao_turno}}</strong></h1>
            <h3><strong>Valor do Curso: </strong> <font color=green> R$ {{number_format($turma->valor_curso, 2, ',', '.')}} </font></h3>
        </div>
        
        <div class="col-sm-4 col-xs-6">
            <h6>Vagas </h6>
            <h6>Limite: {{$turma->limite_alunos}}</h6>
            <h6>Matriculados: {{$quantMatriculas}}</h6>
            <h6><strong>Disponíveis: 
                @if ($quantVagasDisponiveis > 0)
                    <font color="green">
                @elseif ($quantVagasDisponiveis == 0)
                    <font color="#DF7401">
                @else
                    <font color="red"> 
                @endif
                {{$quantVagasDisponiveis}}
                </font>
            </strong>
            </h6>
        </div>
    </div>

@stop

@section('content')
    <div class="card">
        <form action="{{ route('matriculas.store')}}" class="form" method="POST">
            @csrf
            <input type="hidden" name="fk_id_user_cadastro" value={{Auth::id()}}>
            <input type="hidden" name="fk_id_turma" value="{{$turma->id_turma}}">
            @include('secretaria.paginas.matriculas._partials.form')
        </form>
    </div>
@endsection