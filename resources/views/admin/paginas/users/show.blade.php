@extends('adminlte::page')

@section('title', 'Detalhes Usuário')

@section('content_header')
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Usuários</a>
        </li> 
        <li class="breadcrumb-item">
            <a href="">Dados do usuário</a>
        </li>
    </ol>              
    <h1>Cadastro de usuário <b>{{ $user->name}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Login:</strong> {{ $user->email}}
                </li>
                <li>
                    <strong>Nome:</strong> {{ $user->name}}
                </li>
                
            </ul>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection