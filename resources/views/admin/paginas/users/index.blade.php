@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('users.index') }} " class="">Cadastro de Usuários</a>
        </li> 
    </ol>

    <h1>Cadastro de Usuários <a href="{{ route('users.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('users.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        
            
        <div class="table-responsive">
            <table class="table table-hover">
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
                                    <a href="{{ route('users.unidadesensino', $user->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-building"></i></a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
                {{-- <div class="card-footer">
                    @if (isset($filtros))
                    {!! $users->appends($filtros)->links()!!}
                    @else
                        {!! $users->links()!!}    
                    @endif
                    
                </div> --}}
        </div>
    </div>
@stop