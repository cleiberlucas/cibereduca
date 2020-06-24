@extends('adminlte::page')

@section('title_postfix', ' Tipo de Frequência')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposfrequencias.index') }} " class="">Tipos de Frequência</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Editar</a>
        </li>
    </ol>
    <h1>Editar Tipo de Frequência</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposfrequencias.update', $tipoFrequencia->id_tipo_frequencia)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('pedagogico.paginas.tiposfrequencias._partials.form')
        </form>
    </div>
@endsection