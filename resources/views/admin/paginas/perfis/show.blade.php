@extends('adminlte::page')

@section('title', 'Detalhes Perfil')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="">Dados do perfil</a>
        </li>
    </ol>
    <h1>Perfil de usuário <b>{{ $perfil->perfil}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Perfil:</strong> {{ $perfil->perfil}}
                </li>
                <li>
                    <strong>Descrição:</strong> {{ $perfil->descricao_perfil}}
                </li>
                
            </ul>
            <form action="{{ route('perfis.destroy', $perfil->id_perfil) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection