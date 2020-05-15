@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('permissoes.index') }} " class="">Permissões de Usuários</a>
        </li>
    </ol>

    <h1>Permissões de Usuários <a href="{{ route('permissoes.create') }}" class="btn btn-dark">Cadastrar</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('permissoes.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Permissão</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($permissoes as $permissaol)
                            <tr>
                                <td>
                                    {{$permissaol->permissao}}
                                </td>                                
                                <td style="width=10px;">
                                    <a href="{{ route('permissoes.edit', $permissaol->id_permissao) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('permissoes.show', $permissaol->id_permissao) }}" class="btn btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $permissoes->appends($filtros)->links()!!}
                    @else
                        {!! $permissoes->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop