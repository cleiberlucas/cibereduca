@extends('adminlte::page')

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('anosletivos.index') }} " class="">Anos Letivos</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#" class="">Editar</a>
        </li>
    </ol>

    <h1>Editar Ano Letivo</h1>
@stop

@section('content')

    <div class="card">
        <form action="{{ route('anosletivos.update', $anoLetivo->id_ano_letivo)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.anosletivos._partials.form')
        </form>
    </div>
@endsection