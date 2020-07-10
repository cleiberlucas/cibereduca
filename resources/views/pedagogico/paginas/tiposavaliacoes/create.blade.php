@extends('adminlte::page')

@section('title_postfix', ' Tipo de Avaliação')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposavaliacoes.index') }} " class="">Tipos de Avaliações</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h1>Cadastrar Tipo de Avaliação</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposavaliacoes.store')}}" class="form" method="POST">
            @csrf
            
            @include('pedagogico.paginas.tiposavaliacoes._partials.form')
        </form>
    </div>
@endsection
