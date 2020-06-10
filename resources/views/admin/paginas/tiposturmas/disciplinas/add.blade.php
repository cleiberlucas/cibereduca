@extends('adminlte::page')

@section('title', ' Grade Curricular')

@section('content_header')
    <ol class="breadcrumb">    
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.index') }} " class="">Padrões de Turmas</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('tiposturmas.disciplinas', $tipoTurma->id_tipo_turma) }}" class="">Grade Curricular</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="">Cadastrar</a>
        </li>
    </ol>
    <h1><strong>Grade Curricular - Adicionar Disciplinas</strong></h1>
    <h1><strong>Ano Letivo: </strong>{{$tipoTurma->anoLetivo->ano}}</h1>
    <h1><strong>Padrão de Turma: </strong> {{$tipoTurma->tipo_turma}} - {{$tipoTurma->subNivelEnsino->sub_nivel_ensino}}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('tiposturmas.disciplinas.add', $tipoTurma->id_tipo_turma) }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button class="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="form-group">
                <table class="table table-condensed">
                    <thead>
                        <th width="50px">#</th>
                        <th width="200">Disciplinas disponíveis</th> 
                        <th>Carga Horária Anual</th>                                               
                        <th width="870"></th>
                    </thead>
                    <tbody>                        
                        <form action="{{ route('tiposturmas.disciplinas.vincular', $tipoTurma->id_tipo_turma) }}" method="POST">
                            @csrf

                            @foreach ($disciplinas as $disciplina)
                                <tr>
                                    <td>
                                        <input class="form-control"  type="checkbox" name="disciplinas[]" value={{$disciplina->id_disciplina}}>
                                    </td>
                                    <td>
                                        {{$disciplina->disciplina}}
                                    </td>                                
                                    <td>
                                        <input class="form-control" type="number" name="cargas_horarias[]" placeholder="250">
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan=500>
                                    @include('admin.includes.alerts')

                                    <button type="submit" class="btn btn-success"><i class="fas fa-forward"></i> Adicionar</button>
                                </td>
                            </tr>
                        </form>
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