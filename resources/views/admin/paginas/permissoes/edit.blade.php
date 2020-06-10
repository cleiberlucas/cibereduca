@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('permissoes.index') }} " class="">Permissões de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="">Editar</a>
        </li>
    </ol>
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