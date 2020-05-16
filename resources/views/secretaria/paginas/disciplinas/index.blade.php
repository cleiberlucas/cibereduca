

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Disciplinas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('disciplinas.index') }} " class="">Disciplinas</a>
        </li>
    </ol>
    
    <h1>Disciplinas <a href="{{ route('disciplinas.create') }}" class="btn btn-dark">Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('disciplinas.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>Disciplina</th>
                        <th>Sigla</th>
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($disciplinas as $disciplina)
                            <tr>
                                <td>
                                    {{$disciplina->disciplina}}
                                </td> 
                                <td>
                                    {{$disciplina->sigla_disciplina}}
                                </td>                   
                                <td>
                                    @if ($disciplina->situacao_disciplina == 1)
                                        <b>Ativo</b>
                                    @else
                                        Inativo                                        
                                    @endif
                                    
                                </td>              
                                <td style="width=10px;">
                                    <a href="{{ route('disciplinas.edit', $disciplina->id_disciplina) }}" class="btn btn-info">Editar</a>
                                    <a href="{{ route('disciplinas.show', $disciplina->id_disciplina) }}" class="btn btn-warning">VER</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $disciplinas->appends($filtros)->links()!!}
                    @else
                        {!! $disciplinas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
