@extends('adminlte::page')

<section></section>

@section('title_postfix', ' Arquivo Responsáveis')

@section('content_header')
<ol class="breadcrumb">        
    <li class="breadcrumb-item active" >           
        <a href="{{ route('pessoas.index', 2) }} " class=""> Responsáveis </a>        
    </li>
    <li class="breadcrumb-item active" >
        <a href="#" class="">Arquivo Matrículas</a>
    </li>
</ol>
    
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
           
           @if (count($matriculas) == 0)
               Nenhuma matrícula vinculada a este responsável.
           @endif
        </div>
            
        <div class="table-responsive">
                
                    @foreach ($matriculas as $index => $matricula)
                        @if ($index == 0)
                        <div class="d-flex justify-content-between">
                            <div class="p-2">  
                                <h3>Arquivo do Responsável</h3>                          
                                <h3><b>{{$matricula->responsavel->nome}}</b></h3>
                            </div>                            
                            <div class="p-2"> </div>
                        </div>

                            <table class="table table-hover">
                                <thead>
                                    <td colspan=5>
                                        
                                    </td>
                                </thead>                    
                                
                                <th scope="col">#</th>
                                <th scope="col">Ano</th>
                                <th scope="col">Aluno</th>
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
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" class="btn btn-link">{{$matricula->aluno->nome}}</a>                                    
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
