@extends('adminlte::page')

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <h1>Editar Ano Letivo</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('anosletivos.update', $anoletivo->id_ano_letivo)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.anosletivos._partials.form')
        </form>
    </div>
@endsection