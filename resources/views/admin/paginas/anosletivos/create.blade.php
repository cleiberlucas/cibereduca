@extends('adminlte::page')

@section('title_postfix', ' Anos Letivos')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('anosletivos.index') }} " class="">Anos Letivos</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#" class="">Novo</a>
        </li>
    </ol>
    
    <h1>Cadastrar Ano Letivo</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('anosletivos.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.anosletivos._partials.form')
        </form>
    </div>
@endsection