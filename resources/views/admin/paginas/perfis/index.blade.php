@extends('adminlte::page')

@section('title', 'Rede Educa')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="{{ route('perfis.index') }} " class="">Perfis de Usuários</a>
        </li>
    </ol>

    <h1>Perfis de Usuários <a href="{{ route('perfis.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i>  Cadastrar</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('perfis.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Perfil</th>                        
                        <th width="770">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($perfis as $perfil)
                            <tr>
                                <td>
                                    {{$perfil->perfil}}
                                </td>                                
                                <td style="width=10px;">
                                    <a href="{{ route('perfis.edit', $perfil->id_perfil) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('perfis.show', $perfil->id_perfil) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('perfis.permissoes', $perfil->id_perfil) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-lock"></i></a>
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