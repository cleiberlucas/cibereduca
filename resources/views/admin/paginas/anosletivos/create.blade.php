@extends('adminlte::page')

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <h1>Cadastrar Ano Letivo</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('anosletivos.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.anosletivos._partials.form')
        </form>
    </div>
@endsection