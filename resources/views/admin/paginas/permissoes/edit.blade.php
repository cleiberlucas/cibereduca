@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <h1>Editar Permissão </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('permissoes.update', $permissao->id_permissao)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.permissoes._partials.form')
        </form>
    </div>
@endsection