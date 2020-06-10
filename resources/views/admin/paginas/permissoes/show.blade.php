@extends('adminlte::page')

@section('title', 'Detalhes Permissão')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('permissoes.index') }} " class="">Permissões de Usuários</a>
        </li>
        <li class="breadcrumb-item">
            <a href="">Dados da permissão</a>
        </li>
    </ol>
    <h1>Permissão de usuário <b>- {{ $permissao->permissao}}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <ul>
                <li>
                    <strong>Permissão:</strong> {{ $permissao->permissao}}
                </li>
                <li>
                    <strong>Descrição:</strong> {{ $permissao->descricao_permissao}}
                </li>
                
            </ul>
            <form action="{{ route('permissoes.destroy', $permissao->id_permissao) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>
@endsection