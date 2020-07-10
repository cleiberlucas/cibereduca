@extends('adminlte::page')

@section('title_postfix', ' Tipo de Avaliação')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposavaliacoes.index') }} " class="">Tipos de Avaliação</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Editar</a>
        </li>
    </ol>
    <h1>Editar Tipo de Avaliação</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposavaliacoes.update', $tipoAvaliacao->id_tipo_avaliacao)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('pedagogico.paginas.tiposavaliacoes._partials.form')
        </form>
    </div>
@endsection