@extends('adminlte::page')

@section('title_postfix', ' Turmas')

@section('content_header')
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" >
            <a href="#" class="">Pedagógico</a>
        </li>
        <li class="breadcrumb-item active" >
            <a href="{{route('turmas.index.notas')}} " class="">Turmas</a>
        </li>
    </ol>
    <h4>Lançamentos de Notas</h4>    
@stop

@section('content')
    <div class="container-fluid">
        <div class="card-header">
            <form action="{{ route('turmas.notas.search') }}" method="POST" class="form form-inline">
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
                    <th >Notas</th>
                </thead>
                <tbody>                        
                    @foreach ($turmas as $index => $turma)
                        <tr>
                            <th scope="row">{{$index+1}}</th>
                            <td>
                                {{$turma->ano}}
                            </td>
                            <td>
                                <a href="{{ route('turmas.notas', $turma->id_turma) }}" class="btn btn-link">{{$turma->nome_turma}}</a> {{$turma->sub_nivel_ensino}}
                            </td>                                 
                            <td>
                                {{$turma->descricao_turno}}
                            </td>
                            <td style="width=10px;">
                                <a href="{{ route('turmas.notas', $turma->id_turma) }}" class="btn btn-sm btn-outline-success"><i class="far fa-file-alt"></i></a>
                                {{-- <a href="{{ route('turmas.frequencias', $turma->id_turma) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-user-check"></i></a>
                                <a href="{{ route('turmas.periodosletivos', $turma->id_turma) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-key"></i></a> --}}
                                
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
