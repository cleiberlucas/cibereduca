@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <h1>Cadastrar Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('turmas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.turmas._partials.form')
        </form>
    </div>
@endsection