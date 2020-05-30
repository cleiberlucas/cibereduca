@extends('adminlte::page')

@section('title_postfix', ' Tipos de Documentos')

@section('content_header')
    <h1>Cadastrar Tipo de Documento </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposdocumentos.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.tiposdocumentos._partials.form')
        </form>
    </div>
@endsection