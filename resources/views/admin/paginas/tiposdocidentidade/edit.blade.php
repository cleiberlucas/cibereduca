@extends('adminlte::page')

@section('title_postfix', ' Tipo Documento Identidade')

@section('content_header')
    <h1>Editar Tipo Documento Identidade </h1>
    
@stop

@section('content')
    <div class="card">
        <form action="{{ route('tiposdocidentidade.update', $tipoDocIdentidade->id_tipo_doc_identidade)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.tiposdocidentidade._partials.form')
        </form>
    </div>
@endsection