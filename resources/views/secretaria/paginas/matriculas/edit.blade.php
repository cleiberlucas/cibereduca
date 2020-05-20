@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <h1>Editar Matrícula</h1>
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