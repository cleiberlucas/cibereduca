

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Turmas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('turmas.index') }} " class="">Turmas</a>
        </li>
    </ol>
    
    <h1>Turmas <a href="{{ route('turmas.create') }}" class="btn btn-success"> <i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('turmas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Turma" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Ano</th>                        
                        <th>Padrão Turma</th>
                        <th>Turma</th>                        
                        <th>Turno</th>
                        <th>Vagas</th>
                        <th>Localização</th>
                        <th>Limite vagas</th>                        
                        <th>Situação</th>
                        <th >Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($turmas as $index => $turma)
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$turma->tipoTurma->anoLetivo->ano}}
                                </td>
                                <td>
                                    {{$turma->tipoTurma->tipo_turma}} - {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                                </td> 
                                <td>
                                    {{$turma->nome_turma}}
                                </td>                                  
                                <td>
                                    {{$turma->turno->descricao_turno}}
                                </td>
                                <td>
                                    {{$quantVagas[$turma->id_turma]}}
                                </td> 
                                <td>
                                    {{$turma->localizacao}}
                                </td>
                                <td>
                                    {{$turma->limite_alunos}}
                                </td>     
                                <td>
                                    @if ($turma->situacao_turma == 1)
                                        <b>Aberta</b>
                                    @else
                                        Encerrada                                        
                                    @endif                                    
                                </td>       
                                <td style="width=10px;">
                                    <a href="{{ route('matriculas.index', $turma->id_turma) }}" class="btn btn-sm btn-success">Matrículas</a>
                                    <a href="{{ route('turmas.edit', $turma->id_turma) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('turmas.show', $turma->id_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $turmas->appends($filtros)->links()!!}
                    @else
                        {!! $turmas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
