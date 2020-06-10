@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
<ol class="breadcrumb">       
    <li class="breadcrumb-item active" >
        <a href="{{ route('users.index') }} " class="">Usuários</a>
    </li> 
    <li class="breadcrumb-item">
        <a href="">Editar</a>
    </li>
</ol>              
    <h1>Editar Usuário </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ route('users.update', $user->id)}}" class="form" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.paginas.users._partials.form')
        </form>
    </div>
@endsection