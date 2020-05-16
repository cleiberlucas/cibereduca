@extends('adminlte::page')

@section('title_postfix', ' Disciplinas')

@section('content_header')
    <h1>Editar Disciplina </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('disciplinas.update', $disciplina->id_disciplina)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('secretaria.paginas.disciplinas._partials.form')
        </form>
    </div>
@endsection