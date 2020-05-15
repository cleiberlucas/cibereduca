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

    <h1>Adicionar Permissões ao Perfil <strong>{{$perfil->perfil}}</strong> 
        
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('perfis.permissoes.add', $perfil->id_perfil) }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th width="50px">#</th>
                        <th>Permissões disponíveis</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        <form action="{{ route('perfis.permissoes.vincular', $perfil->id_perfil) }}" method="POST">
                            @csrf

                            @foreach ($permissoes as $permissao)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="permissoes[]" value={{$permissao->id_permissao}}>
                                    </td>
                                    <td>
                                        {{$permissao->permissao}}
                                    </td>                                
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan=500>
                                    @include('admin.includes.alerts')

                                    <button type="submit" class="btn btn-success">Adicionar</button>
                                </td>
                            </tr>
                        </form>
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