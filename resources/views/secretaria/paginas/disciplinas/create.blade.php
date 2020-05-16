@extends('adminlte::page')

@section('title_postfix', ' Disciplinas')

@section('content_header')
    <h1>Cadastrar Disciplina </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('disciplinas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.disciplinas._partials.form')
        </form>
    </div>
@endsection