@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="">Cadastrar</a>
        </li>
    </ol>
    <h1>Cadastrar Perfil </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('perfis.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.perfis._partials.form')
        </form>
    </div>
@endsection