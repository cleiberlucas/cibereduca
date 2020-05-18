@extends('adminlte::page')

@section('title_postfix', ' Turma')

@section('content_header')
    <h1>Editar Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('turmas.update', $turma->id_turma)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('secretaria.paginas.turmas._partials.form')
        </form>
    </div>
@endsection