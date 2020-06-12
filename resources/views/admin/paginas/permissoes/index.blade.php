@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('permissoes.index') }} " class="">Permissões de Usuários</a>
        </li>
    </ol>

    <h1>Permissões de Usuários <a href="{{ route('permissoes.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('permissoes.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Permissão</th>  
                        <th>Observações</th>                      
                        <th width="570">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($permissoes as $permissao)
                            <tr>
                                <td>
                                    {{$permissao->permissao}}
                                </td>                           
                                <td>
                                    {{$permissao->descricao_permissao}}
                                </td>     
                                <td style="width=10px;">
                                    <a href="{{ route('permissoes.edit', $permissao->id_permissao) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('permissoes.show', $permissao->id_permissao) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
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