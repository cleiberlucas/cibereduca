@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Pasta do Aluno')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcamb-item">
            <a href="">Cadastrar</a> / 
        </li>
        <li class="breadcrumb-item active" >
            
           {{--  <a href="{{ route('pessoas.index', 1) }} " class=""> 
                @if ($tipo_pessoa == 1)
                    Aluno
                @else
                    Responsável
                @endif
            </a> --}}
        </li>
    </ol>
    
   {{--  <h1> @if ($tipo_pessoa == 1)
            Aluno
            <a href="{{ route('pessoas.create.aluno', $tipo_pessoa) }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
        @else
            Responsável
            <a href="{{ route('pessoas.create.responsavel') }}" class="btn btn-success"><i class="fas fa-plus-square"></i> Cadastrar</a></h1>    
        @endif --}}
    
@stop

@section('content')
    <div class="container-fluid">
        {{-- <div class="card-header">
            <form action="{{ route('pessoas.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filtro" placeholder="Nome" class="form-control" value="{{ $filtros['filtro'] ?? '' }}">
                <input type="hidden" name="tipo_pessoa" value="{{$tipo_pessoa}}">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i></button>
            </form>
        </div> --}}
        <div>
           <h3>Pasta do Aluno</h3>
           @if (count($matriculas) == 0)
               Nenhuma matrícula gerada para este aluno.
           @endif
        </div>
            
        <div class="table-responsive">
                <table class="table table-hover">
                    @foreach ($matriculas as $index => $matricula)
                        @if ($index == 0)
                            <thead>
                                <td colspan=5>
                                    <h3><b>{{$matricula->aluno->nome}}</b></h3>
                                </td>
                            </thead>                    
                            
                            <th scope="col">#</th>
                            <th scope="col">Ano</th>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Situação</th>                        
                            <th width="270">Ações</th>
                            
                        @endif

                        <tbody>                        
                            <tr>
                                <th scope="row">{{$index+1}}</th>
                                <td>
                                    {{$matricula->turma->tipoTurma->anoLetivo->ano}}
                                </td>
                                <td>
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-link">{{$matricula->turma->nome_turma}} - {{$matricula->turma->turno->descricao_turno}}</a>                                    
                                </td> 
                                <td>
                                    {{$matricula->situacaoMatricula->situacao_matricula}}                                        
                                </td>              
                                <td >
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('matriculas.documentos', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                    
                                    <a href="{{ route('matriculas.show', $matricula->id_matricula) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                    <a href="" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-contract"></i></a>
                                    <a href="" class="btn btn-sm btn-outline-warning"><i class="fas fa-address-book"></i></i></a>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="card-footer">
                    @if (isset($filtros))
                    {!! $matriculas->appends($filtros)->links()!!}
                    @else
                        {!! $matriculas->links()!!}    
                    @endif
                    
                </div> --}}
        </div>
    </div>
@stop
