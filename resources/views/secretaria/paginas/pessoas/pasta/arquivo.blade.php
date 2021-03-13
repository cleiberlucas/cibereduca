@extends('adminlte::page')



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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
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
                                <h4>Arquivo do Responsável</h4>                          
                                <h4><b>{{$matricula->responsavel->nome}}</b></h4> 
                                
                                <a href="{{ route('captacao.search', ['filtro' => $matricula->fk_id_responsavel]) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-plus-circle"></i>Captações</a>
                                
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
                                    <?php $hash = preg_replace("/[^a-zA-Z0-9\s]/", "", crypt($matricula->id_matricula, 'cs'));?>  
                                    <a href="{{ route('matriculas.edit', $matricula->id_matricula) }}" data-content="Editar Matrícula" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('financeiro.indexAluno', $matricula->fk_id_aluno) }}" data-content="Financeiro" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-primary"> <i class="fas fa-dollar-sign"></i></a>                                    
                                    <a href="{{ route('matriculas.documentos', $matricula->id_matricula) }}" data-content="Entrega documentos" data-toggle="popover" data-trigger="hover" class="btn btn-sm btn-outline-success"><i class="fas fa-tasks"></i></a>                                    
                                    <a href="{{ route('matriculas.show', $matricula->id_matricula) }}"  class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>                                    
                                    <a href="{{ route('matriculas.contrato', [$matricula->id_matricula, $hash]) }}" data-content="Contrato" data-toggle="popover" data-trigger="hover" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-contract"></i></a>
                                    <a href="{{ route('matriculas.requerimento', $matricula->id_matricula) }}" data-content="Requerimento" data-toggle="popover" data-trigger="hover"  target="_blank" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-signature"></i></a>
                                    
                                   {{--  <form action="{{ route('captacao.search') }}" method="POST" class="form form-inline">
                                        @csrf
                                    <input type="text" name="filtro" size="30" placeholder="Informe interessado ou aluno" class="form-control" value="{{ $filtros['$matricula->responsavel->nome'] ?? '' }}" hidden>
                                        <button type="submit"class="btn btn-sm btn-outline-alert"><i class="fas fa-plus-circle"></i></button>
                                    </form> --}}
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
    <script>
        $('[data-toggle="popover"]').popover();  
    </script>
@stop
