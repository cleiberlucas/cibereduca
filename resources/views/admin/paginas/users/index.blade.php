@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Cadastro de Usuários</a>
        </li>
    </ol>

    <h1>Cadastro de Usuários <a href="{{ route('users.create') }}" class="btn btn-dark">Cadastrar</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('users.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Usuário (login)</th>
                        <th>Nome</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($users as $user)
                            <tr>
                                <td> {{$user->email}} </td>                                
                                <td> {{$user->name}} </td>                                
                                <td style="width=10px;">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $users->appends($filtros)->links()!!}
                    @else
                        {!! $users->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop