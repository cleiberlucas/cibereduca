@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div class="p-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" >
                    <a href="{{ route('turmas.index') }} " class="">Turmas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="{{route('matriculas.index', $matricula->fk_id_turma)}}" class="">Matrículas</a>
                </li>
                <li class="breadcrumb-item active" >
                    <a href="#" class="">Editar Matrícula</a>
                </li>
            </ol>
            
            <h1><strong>Editar </strong> Matrícula {{$matricula->ano}} - 
            Turma: {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</h1>
            <h3><strong>Valor do Curso: </strong><font color=green> R$ {{number_format($matricula->valor_curso, 2, ',', '.')}} </font> </h3>

        </div>
        <div class="p-2">
            <img src="{{url("storage/$matricula->foto")}}" alt="" width="100" heigth="200">
        </div>
        <div class="p-2"> </div>
    </div>

<div class="" align="center">
    <h1><strong> Aluno: {{$matricula->nome_aluno}}</strong></h1>
</div>

@stop

@section('content')
    <div class="card">
        <form action="{{ route('matriculas.update', $matricula->id_matricula)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="fk_id_turma" value="{{$matricula->fk_id_turma ?? $turma->id_turma}}">
            <input type="hidden" name="fk_id_aluno" value="{{$matricula->fk_id_aluno ?? ''}}">
            <input type="hidden" name="fk_id_user_altera" value={{Auth::id()}}>
            @include('secretaria.paginas.matriculas._partials.form')
        </form>
    </div>
@endsection