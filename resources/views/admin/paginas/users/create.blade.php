@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Usuários</a>
        </li> 
        <li class="breadcrumb-item">
            <a href="">Cadastrar</a>
        </li>
    </ol>              
    <h1>Cadastrar Usuário </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('users.store')}}" class="form" method="POST">
            @csrf
            
            @include('admin.paginas.users._partials.form')
        </form>
    </div>
@endsection