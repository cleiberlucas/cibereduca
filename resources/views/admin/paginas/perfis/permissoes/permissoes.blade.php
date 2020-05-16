@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
    </ol>

    <h1>Permissões do Perfil <strong>{{$perfil->perfil}}</strong> 
    <a href="{{ route('perfis.permissoes.add', $perfil->id_perfil) }}" class="btn btn-dark">ADD permissão</a></h1>

@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('perfis.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <th>Permissões</th>                        
                    <th width="270">Ações</th>
                </thead>
                <tbody>                        
                    @foreach ($permissoes as $permissao)
                        <tr>
                            <td>
                                {{$permissao->permissao}}
                            </td>                                
                            <td style="width=10px;">                                                                    
                                <a href="{{ route('perfis.permissoes.remover', [$perfil->id_perfil, $permissao->id_permissao]) }}" class="btn btn-danger">Remover</a> 
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