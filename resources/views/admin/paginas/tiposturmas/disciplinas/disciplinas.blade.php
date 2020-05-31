@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Grades Curriculares')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{ route('tiposturmas.index') }} " class="">Padrão de Turmas</a>
        </li>
    </ol>

    <div align="center"><h1><strong>Grade Curricular</strong></h1></div>
    <h1><strong>Ano Letivo: </strong>{{$tipoTurma->anoLetivo->ano}}</h1>
    <h1><Strong>Padrão de Turma: </Strong>{{$tipoTurma->tipo_turma}} - {{$tipoTurma->subNivelEnsino->sub_nivel_ensino}}</h1>
    
    <h1><a href="{{ route('tiposturmas.disciplinas.add', $tipoTurma->id_tipo_turma) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Adicionar disciplina</a></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            {{-- <form action="{{ route('gradescurriculares.search') }}" method="POST" class="form form-inline">
                @csrf
            <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit"class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form> --}}
        </div>
        <div class="card-body">
                <table class="table table-condensed">
                    <thead>
                        <th>#</th>
                        <th>Disciplina</th>
                        <th>Carga horária anual</th>                        
                        <th width="570">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($disciplinas as $index => $disciplina)
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$disciplina->disciplina}}
                                </td> 
                                <td>
                                    {{$disciplina->carga_horaria_anual}}
                                </td>                                                                 
                                <td style="width=10px;">                                    
                                    <a href="{{ route('tiposturmas.disciplinas.remover', [$tipoTurma->id_tipo_turma, $disciplina->id_disciplina]) }}" class="btn btn-sm btn-outline-danger"><i class="far fa-trash-alt"></i></a>
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
