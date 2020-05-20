@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <h1>Matricular Aluno</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('matriculas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.matriculas._partials.form')
        </form>
    </div>
@endsection