@extends('adminlte::page')

@section('title_postfix', ' Desconto Curso')

@section('content_header')
    <h1>Editar Desconto Curso </h1>
    
@stop

@section('content')
    <div class="card">
        <form action="{{ route('descontoscursos.update', $descontoCurso->id_tipo_desconto_curso)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.descontoscursos._partials.form')
        </form>
    </div>
@endsection