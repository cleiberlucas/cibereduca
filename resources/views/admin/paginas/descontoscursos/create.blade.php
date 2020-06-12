@extends('adminlte::page')

@section('title_postfix', ' Desconto Curso')

@section('content_header')
    <h1>Cadastrar Desconto Curso </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('descontoscursos.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.descontoscursos._partials.form')
        </form>
    </div>
@endsection