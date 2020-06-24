@extends('adminlte::page')

@section('title_postfix', ' Tipo de Frequência')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposfrequencias.index') }} " class="">Tipos de Frequências</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h1>Cadastrar Tipo de Frequência</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposfrequencias.store')}}" class="form" method="POST">
            @csrf
            
            @include('pedagogico.paginas.tiposfrequencias._partials.form')
        </form>
    </div>
@endsection
