@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Editar Unidade Ensino </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('unidadesensino.update', $unidadeensino->id_unidade_ensino)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.unidadesensino._partials.form')
        </form>
    </div>
@endsection