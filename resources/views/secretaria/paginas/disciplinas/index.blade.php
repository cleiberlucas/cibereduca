

@extends('adminlte::page')



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
    
    <h1>Disciplinas <a href="{{ route('disciplinas.create') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('disciplinas.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
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
                                    <a href="{{ route('disciplinas.edit', $disciplina->id_disciplina) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('disciplinas.show', $disciplina->id_disciplina) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
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
