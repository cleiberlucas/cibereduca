@extends('adminlte::page')

@section('title_postfix', ' Matrículas')

@section('content_header')
    <h1><strong>Matricular Aluno - {{$turma->ano}}</strong></h1>
    <h1><strong>Turma: {{$turma->nome_turma}} - {{$turma->descricao_turno}}</strong></h1>

@stop

@section('content')
    <div class="card">
        <form action="{{ route('matriculas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.matriculas._partials.form')
        </form>
    </div>
@endsection