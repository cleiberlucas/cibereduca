

@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Turmas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{url('pedagogico/turmas')}} " class="">Diários</a>
        </li>
    </ol>
    <h4>Lançamentos no Diário</h4>
    <h6>Legenda:</h6>
    <h6><a href="#" class="btn btn-sm btn-outline-success disabled" ><i class="fas fa-chalkboard"></i></a> Conteúdos Lecionados &nbsp&nbsp&nbsp&nbsp&nbsp  <a href="#" class="disabled btn btn-sm btn-outline-info"> <i class="fas fa-user-check"></i></a> Frequências</h6>
    <h6><a href="#" class="btn btn-sm btn-outline-warning disabled"><i class="fas fa-key"></i></a> Abrir ou fechar período letivo de uma turma.</h6>
    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('turmas.diarios.search') }}" method="POST" class="form form-inline">
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
                    <th>Turma</th>                        
                    <th>Turno</th>
                    
                    {{-- <th>Situação</th> --}}
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
                                {{$turma->nome_turma}} {{$turma->tipoTurma->subNivelEnsino->sub_nivel_ensino}}
                            </td>                                 
                            <td>
                                {{$turma->turno->descricao_turno}}
                            </td>
                            
                            {{-- <td>
                                @if ($turma->situacao_turma == 1)
                                    <b>Aberta</b>
                                @else
                                    Encerrada                                        
                                @endif                                    
                            </td>  --}}      
                            <td style="width=10px;">
                                <a href="{{ route('turmas.conteudoslecionados', $turma->id_turma) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-chalkboard"></i></a>
                                <a href="{{ route('turmas.frequencias', $turma->id_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-user-check"></i></a>
                                <a href="{{ route('turmas.periodosletivos', $turma->id_turma) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-key"></i></a>                                
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
