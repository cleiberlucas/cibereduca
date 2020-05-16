@extends('adminlte::page')

@section('title_postfix', ' '.$tipoPessoa)

@section('content_header')
    <h1>Editar {{$tipoPessoa}} </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('pessoas.update', $pessoa->id_pessoa)}}" class="form" method="POST">            
            @csrf
            @method('PUT')
            
            
            @include('secretaria.paginas.pessoas._partials.form')
        </form>
    </div>
@endsection