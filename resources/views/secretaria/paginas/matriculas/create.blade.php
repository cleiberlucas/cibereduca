@extends('adminlte::page')

@section('title_postfix', ' Matrículas')

@section('content_header')
<ol class="breadcrumb">
    <li class="breadcrumb-item active" >
        <a href="{{ route('turmas.index') }} " class="">Turmas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="{{route('matriculas.index', $turma->id_turma)}}" class="">Matrículas</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Nova Matrícula</a>
    </li>
</ol>
    
    <div class="row">
        <div class="col-sm-8 col-xs-6">
            <h3><strong>Matricular </strong> Aluno {{$turma->ano}} - 
            Turma: {{$turma->nome_turma}} - {{$turma->descricao_turno}}</strong></h3>
            <h5><strong>Valor do Curso: </strong> <font color=green> R$ {{number_format($turma->valor_curso, 2, ',', '.')}} </font></h5>
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