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

    <h1>Perfis de Usuários <a href="{{ route('perfis.create') }}" class="btn btn-dark">Cadastrar</a></h1>
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
                        <th>Perfil</th>                        
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($perfis as $perfil)
                            <tr>
                                <td>
                                    {{$perfil->perfil}}
                                </td>                                
                                <td style="width=10px;">
                                    <a href="{{ route('perfis.edit', $perfil->id_perfil) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('perfis.show', $perfil->id_perfil) }}" class="btn btn-warning">VER</a>
                                    <a href="{{ route('perfis.permissoes', $perfil->id_perfil) }}" class="btn btn-warning"><i class="fas fa-lock"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $perfis->appends($filtros)->links()!!}
                    @else
                        {!! $perfis->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop