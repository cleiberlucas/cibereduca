@extends('adminlte::page')

@section('title', 'Rede Educa')

    @section('content_header')
        <ol class="box">
            <li class="breadcamb-item">
                <a href="">Cadastrar</a> / 
                <a href="{{ route('unidadesensino.index') }} " class="">Unidades Ensino</a>
            </li>
        </ol>

        <h1>Unidade Ensino <a href="{{ route('unidadesensino.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>
    @stop

    @section('content')
    
    <div class="container-fluid">
        
            <div class="box-header">
                <form action="{{ route('unidadesensino.search') }}" method="POST" class="form form-inline">
                    @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                    <button class="submit" class="btn btn-dark">Filtrar</button>
                </form>
            </div>
            <div class="card-body">
                @include('admin.includes.alerts')
                <table class="table table-condensed">
                    <thead>
                        <th>Campus</th>
                        <th>Telefone</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>
                        @foreach ($unidadesensino as $unidadeEnsino)
                            <tr>
                                <td>
                                    {{$unidadeEnsino->nome_fantasia}}
                                </td>
                                <td>
                                    {{$unidadeEnsino->telefone}}
                                </td>
                                <td style="width=10px;">
                                    <a href="{{ route('unidadesensino.edit', $unidadeEnsino->id_unidade_ensino)}}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('unidadesensino.show', $unidadeEnsino->id_unidade_ensino) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $unidadesensino->appends($filtros)->links()!!}
                    @else
                        {!! $unidadesensino->links()!!}    
                    @endif
                        
                </div>
         
    </div>
@stop

