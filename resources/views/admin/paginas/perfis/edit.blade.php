@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="">Editar</a>
        </li>
    </ol>
    <h1>Editar Perfil </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('perfis.update', $perfil->id_perfil)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.perfis._partials.form')
        </form>
    </div>
@endsection