@extends('adminlte::page')

@section('title_postfix', ' Períodos Letivos')

@section('content_header')
<ol class="breadcrumb">   
    <li class="breadcrumb-item active" >
        <a href="{{ route('periodosletivos.index') }} " class="">Períodos Letivos</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="#" class="">Editar</a> 
    </li>
</ol>
    <h1>Editar Período Letivo</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('periodosletivos.update', $periodoLetivo->id_periodo_letivo)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.periodosletivos._partials.form')
        </form>
    </div>
@endsection