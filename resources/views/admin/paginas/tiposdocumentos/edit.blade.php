@extends('adminlte::page')

@section('title_postfix', ' Tipos de Documento')

@section('content_header')
    <h1>Editar Tipo de Documento </h1>
    
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposdocumentos.update', $tipoDocumento->id_tipo_documento)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.tiposdocumentos._partials.form')
        </form>
    </div>
@endsection