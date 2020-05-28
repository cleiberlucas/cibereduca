@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
<h1><strong>Editar Matrícula - {{$matricula->ano}}</strong></h1>
<h1>Turma: {{$matricula->nome_turma}} - {{$matricula->descricao_turno}}</h1>
<h1><strong>Aluno: {{$matricula->nome_aluno}}</strong></h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('matriculas.update', $matricula->id_matricula)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('secretaria.paginas.matriculas._partials.form')
        </form>
    </div>
@endsection