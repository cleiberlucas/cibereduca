@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
<ol class="breadcrumb">        
    <li class="breadcrumb-item active" >        
        <a href="{{ route('unidadesensino.index') }} " class="">Unidades Ensino</a>
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Editar</a>
    </li>
</ol> 
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