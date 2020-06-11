@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" >
            <a href="{{ route('turmas.index') }} " class="">Turmas</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h1>Cadastrar Turma</h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('turmas.store')}}" class="form" method="POST">
            @csrf
            
            @include('secretaria.paginas.turmas._partials.form')
        </form>
    </div>
@endsection