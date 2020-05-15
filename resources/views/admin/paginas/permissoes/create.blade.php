@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Cadastrar Permissão </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('permissoes.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.permissoes._partials.form')
        </form>
    </div>
@endsection