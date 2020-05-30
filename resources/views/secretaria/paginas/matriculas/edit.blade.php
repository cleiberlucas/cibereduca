@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
<h1><strong>Editar </strong> Matrícula {{$matricula->ano}} - 
Turma: {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</h1>
<h3><strong>Valor do Curso: </strong><font color=green> R$ {{$matricula->valor_padrao_mensalidade}} </font> </h3>
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