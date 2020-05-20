@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Matrículas')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            {{-- <a href="{{ route('matriculas.index', ) }} " class="">Matrículas</a> --}}
        </li>
    </ol>
    
    <h1>Ano letivo - {{$matriculas['0']['ano']}}</h1>
    <h1>Alunos Matriculados - {{$matriculas['0']['nome_turma']}}</h1>
    <a href="{{ route('matriculas.create') }}" class="btn btn-success"> Nova Matrícula</a></h1>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('matriculas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Aluno" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <button type="submit" class="btn btn-outline-info">Filtrar </button>
            </form>
        </div>
        <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th>#</th>
                        <th>Nome</th>                                                
                        <th>Situação</th>
                        <th width="270">Ações</th>
                    </thead>
                    <tbody>                        
                        @foreach ($matriculas as $index => $matricula)
                            <tr>
                                <th scope="row">{{$index+1}}</th>                                
                                <td>
                                    {{$matricula->aluno->nome}}
                                </td> 
                                <td>
                                    {{$matricula->situacaoMatricula->situacao_matricula}}
                                </td>                                                   
                                                                      
                                <td style="width=10px;">                                
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-sm btn-primary">Editar</a>
                                    <a href="{{ route('matriculas.show', $matricula->id_matricula) }}" class="btn btn-sm btn-info">VER</a>
                                    <a href="" class="btn btn-sm btn-success">Contrato</a>
                                    <a href="" class="btn btn-sm btn-warning">Ocorrências</a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    @if (isset($filtros))
                    {!! $matriculas->appends($filtros)->links()!!}
                    @else
                        {!! $matriculas->links()!!}    
                    @endif
                    
                </div>
        </div>
    </div>
@stop
